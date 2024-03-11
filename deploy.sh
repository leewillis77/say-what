#!/bin/sh

# By Mike Jolley, based on work by Barry Kooij ;)
# License: GPL v3

# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>

# Based on
# https://raw.githubusercontent.com/mikejolley/github-to-wordpress-deploy-script/master/release.sh
# https://github.com/deanc/wordpress-plugin-git-svn

# The slug of your WordPress.org plugin
PLUGIN_SLUG="say-what"
MAINFILE="say-what.php"
CURRENTDIR=`pwd`
GITPATH="$CURRENTDIR"

set -e
clear

# Check version in readme.txt is the same as plugin file after translating both to unix line breaks to work around grep's failure to identify mac line breaks
VERSION=`grep "^Stable tag:" $GITPATH/readme.txt | awk -F' ' '{print $NF}'`
echo "readme.txt version: $VERSION"
MAINFILEVERSION=`grep "^[ \*]*Version:" $GITPATH/$MAINFILE | awk -F' ' '{print $NF}'`
echo "$MAINFILE version: $MAINFILEVERSION"

if [ "$VERSION" != "$MAINFILEVERSION" ]; then
  echo "Version in readme.txt & $MAINFILE don't match. Exiting....";
  exit 1;
fi

echo ""
echo "Before continuing, confirm that you have done the following :)"
echo ""
read -p " - Added a changelog for "${VERSION}"?"
read -p " - Updated the POT file?"
read -p " - Committed all changes up to GITHUB?"
echo ""
read -p "PRESS [ENTER] TO BEGIN RELEASING "${VERSION}
clear

# VARS
ROOT_PATH="$CURRENTDIR"
TEMP_GITHUB_REPO="$CURRENTDIR"
TEMP_SVN_REPO="/tmp/${PLUGIN_SLUG}-svn"
SVN_REPO="http://plugins.svn.wordpress.org/"${PLUGIN_SLUG}"/"

# CHECKOUT SVN DIR IF NOT EXISTS
if [[ ! -d $TEMP_SVN_REPO ]];
then
	echo "Checking out WordPress.org plugin repository"
	svn checkout $SVN_REPO $TEMP_SVN_REPO || { echo "Unable to checkout repo."; exit 1; }
fi

# MOVE INTO GIT DIR
cd $TEMP_GITHUB_REPO

# Tag release
if [ ! $(git tag -l "v$VERSION") ]; then
  echo "Tagging version in git: v$VERSION"
  git tag "v$VERSION"
  git push origin "v$VERSION"
fi

# MOVE INTO SVN DIR
cd $TEMP_SVN_REPO

# UPDATE SVN
echo "Updating SVN"
svn update || { echo "Unable to update SVN."; exit 1; }

# DELETE TRUNK
echo "Removing SVN trunk"
rm -Rf trunk/

# COPY GIT DIR TO TRUNK
echo "Copying code from git repo"
cp -R $TEMP_GITHUB_REPO trunk/

# REMOVE UNWANTED FILES & FOLDERS
echo "Removing unwanted files"
cd $TEMP_SVN_REPO/trunk
composer install --no-dev
rm -Rf .git
rm -Rf .github
rm -Rf .wordpress-org
rm -Rf tests
rm -Rf node_modules
rm -Rf .DS_Store
rm -f .gitattributes
rm -f .gitignore
rm -f .gitmodules
rm -f .nvmrc
rm -f Gruntfile.js
rm -f deploy.sh
rm -f package.json
rm -f package-lock.json
rm -f composer.json
rm -f webpack.config.js
rm -f composer.lock
rm -f phpcs.xml
rm -f phpunit.xml
rm -f phpunit.xml.dist
rm -f phpmd-ruleset.xml
rm -f README.md
rm -f .editorconfig
cd $TEMP_SVN_REPO

# DO THE ADD ALL NOT KNOWN FILES UNIX COMMAND
svn add --force * --auto-props --parents --depth infinity -q

# DO THE REMOVE ALL DELETED FILES UNIX COMMAND
MISSING_PATHS=$( svn status | sed -e '/^!/!d' -e 's/^!//' )

# iterate over filepaths
for MISSING_PATH in $MISSING_PATHS; do
  echo "Removing $MISSING_PATH"
    svn rm --force "${MISSING_PATH}@"
done

cd $TEMP_SVN_REPO

# COPY TRUNK TO TAGS/$VERSION
echo "Copying trunk to new tag"
svn copy trunk tags/${VERSION} || { echo "Unable to create tag."; exit 1; }

# DO SVN COMMIT
clear
echo "Showing SVN status"
svn status

# PROMPT USER
echo ""
read -p "PRESS [ENTER] TO COMMIT RELEASE "${VERSION}" TO WORDPRESS.ORG"
echo ""

# DEPLOY
echo ""
echo "Committing to WordPress.org...this may take a while..."
svn commit -m "Release ${VERSION}" || { echo "Unable to commit."; exit 1; }

# REMOVE THE TEMP DIRS
echo "CLEANING UP"
rm -Rf $TEMP_SVN_REPO

# DONE, BYE
echo "RELEASER DONE :D"
