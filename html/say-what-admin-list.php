<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h2><?php _e( 'Text changes', 'say-what' ); ?><a href="tools.php?page=say_what_admin&amp;say_what_action=addedit"
                                                     class="add-new-h2"><?php _e( 'Add New', 'say-what' ); ?></a></h2>
	<?php
	$list_table_instance = new SayWhatListTable( $this->settings );
	$list_table_instance->prepare_items();
	$list_table_instance->display();
	?>
    <div class="upgrades-wrapper">
            <div class="saywhat-gopro">
                <div>
                    <h2>Go pro</h2>
                    <hr>
                    <p>
                        <a href="https://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade" target="_blank" rel="noopener noreferrer">Say What? Pro</a> makes it even easier for you to change strings:
                    <ul class="ul-disc">
                        <li>
                            <a href="https://plugins.leewillis.co.uk/doc_post/string-discovery/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgradesd"
                               target="_blank" rel="noopener noreferrer">String Discovery</a> and autocomplete - find the strings you need without
                            diving through code.
                        </li>
                        <li>
                            <a href="https://plugins.leewillis.co.uk/doc_post/replace-words-across-whole-site/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgradesd"
                               target="_blank" rel="noopener noreferrer">Wildcard string replacements</a> - replace individual words, or fragments
                            across your whole site.
                        </li>
                        <li>
                            <a href="https://plugins.leewillis.co.uk/doc_post/multi-lingual-string-replacements/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrademl" target="_blank" rel="noopener noreferrer">Multi-lingual support</a> - set different replacements for different languages.</a></li>
                        <li>Import &amp; export your strings between sites.</li>
                    </ul>
                    </p>
                    <p><strong>Upgrade to the Pro version today, and <em>save 15%</em> with the code <code>WPSAYWHAT</code> at checkout.</strong></p>
                    <p>
                    <center>
                        <a href="https://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgradebtn"
                           class="button button-primary" target="_blank" rel="noopener noreferrer">Go Pro now &raquo;</a></center>
                    </p>
                </div>
            </div>
            <div class="treeware">
                <a href="https://ecologi.com/ademtisoftware?gift-trees" target="_blank" rel="noopener noreferrer nofollow">
                    <img src="<?php echo esc_attr( plugins_url( 'say-what/img/treeware.svg' ) ); ?>" alt="Treeware">
                </a>
                <hr>
                <p>You're free to use this package for free, but if it makes it to your production environment please buy the world some trees.</p>
                <p>It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to <a href="https://www.bbc.co.uk/news/science-environment-48870920" target="_blank" rel="noopener noreferrer nofollow">plant trees</a>. If you support this package and contribute to the our forest you’ll be creating employment for local families and restoring wildlife habitats.</p>
                <p>You can buy trees here <a target="_blank" rel="noopener noreferrer nofollow" href="https://ecologi.com/ademtisoftware?gift-trees">ecologi.com/ademtisoftware</a>
                </p>
            </div>
    </div>
</div>
