<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h2><?php esc_html_e( 'Text changes', 'say-what' ); ?><a href="tools.php?page=say_what_admin&amp;say_what_action=addedit"
                                                     class="add-new-h2"><?php esc_html_e( 'Add New', 'say-what' ); ?></a></h2>
	<?php
	$list_table_instance = new Ademti\SayWhat\ListTable();
	$list_table_instance->prepare_items();
	$list_table_instance->display();
	?>

    <div class="swp-upgrades-wrapper">
        <div class="swp-cpw">
            <div class="swp-img-container">
                <a href="https://ecologi.com/ademtisoftware?gift-trees&amp;r=ademtisoftware" target="_blank" rel="noopener noreferrer nofollow">
                    <img src="<?php echo esc_attr( plugins_url( 'say-what/img/cpw.png' ) ); ?>"
                         alt="<?php esc_attr_e( "We're a climate positive workforce", 'say-what' ); ?>">
                </a>
            </div>
            <div class="swp-intro-container">
                <p class="swp-intro"><?php esc_html_e( 'Using this plugin on your live site? Please buy the world some trees...', 'say-what' ); ?></p>
                <div><a class="button" target="_blank" rel="noopener noreferrer nofollow"
                        href="https://ecologi.com/ademtisoftware?gift-trees&amp;r=ademtisoftware"><?php esc_html_e( 'Support this free plugin by planting trees', 'say-what' ); ?></a></div>
            </div>
            <div class="swp-content-container">
                <p><?php
					// Translators: %1$s is opening <a> tag, %2%s is the closing tag.
                    printf(esc_html__( 'As a business, we already donate a percentage of our profits from premium plugins to global climate change projects. You\'re free to use package free of charge, but if you do, please consider %1$sbuying the world some trees%2$s in return. You’ll be creating employment for local families and restoring wildlife habitats.', 'say-what' ),'<a target="_blank" rel="noopener noreferrer nofollow" href="https://ecologi.com/ademtisoftware?gift-trees&amp;r=ademtisoftware">','</a>');
                    ?></p>
            </div>
        </div>
        <div class="saywhat-gopro">
            <div>
                <h1><?php esc_html_e( 'Save time - Upgrade to Pro today', 'say-what' ); ?></h1>
                <a href="https://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgradegraphic" target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo esc_attr( plugins_url( 'say-what/img/go-pro.png' ) ); ?>"
                     alt="<?php esc_attr_e( "Save time - go Pro today!", 'say-what' ); ?>" width="100%">
                </a>
                <p>
                    <?php
					// Translators: %1$s is opening <a> tag, %2%s is the closing tag.
                    printf(esc_html__( '%1$sSay What? Pro%2$s makes it even easier for you to change strings:', 'say-what' ),'<a href="https://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade" target="_blank" rel="noopener noreferrer">','</a>');
                    ?>
                <ul class="ul-disc">
                    <li>
                        <?php
						// Translators: %1$s is opening <a> tag, %2%s is the closing tag.
                        printf(esc_html__( '%1$sString Discovery%2$s and autocomplete - find the strings you need without diving through code. Works with server-side and Javascript-rendered strings.', 'say-what' ),'<a href="https://plugins.leewillis.co.uk/doc_post/string-discovery/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade"
                           target="_blank" rel="noopener noreferrer">','</a>');
                        ?>
                    </li>
                    <li>
                        <?php
						// Translators: %1$s is opening <a> tag, %2%s is the closing tag.
                        printf(esc_html__( '%1$sWildcard string replacements%2$s - replace individual words, or fragments across your whole site.', 'say-what' ),'<a href="https://plugins.leewillis.co.uk/doc_post/replace-words-across-whole-site/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade" target="_blank" rel="noopener noreferrer">','</a>');
                        ?>
                    </li>
                    <li>
                        <?php
						// Translators: %1$s is opening <a> tag, %2%s is the closing tag.
                        printf(esc_html__( '%1$sMulti-lingual support%2$s - set different replacements for different languages.', 'say-what' ),'<a href="https://plugins.leewillis.co.uk/doc_post/multi-lingual-string-replacements/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade" target="_blank" rel="noopener noreferrer">','</a>');
                        ?>
					</li>
                    <li>
                        <?php
						// Translators: %1$s is opening <a> tag, %2%s is the closing tag.
                        printf(esc_html__( '%1$sImport &amp; export%2$s your strings between sites.', 'say-what' ),'<a href="https://plugins.leewillis.co.uk/doc_post/import-export-string-replacements/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade">','</a>');
                        ?>
                    </li>
                    <li><?php
						// Translators: %1$s is opening <a> tag, %2%s is the closing tag.
                        printf(esc_html__( '%1$sImproved performance%2$s using text-domain-specific filters.', 'say-what' ),'<em>','</em>');
                        ?></li>
                </ul>
                </p>
                <p>
                    <strong><?php
						// Translators: %1$s / 2 are opening / closing <em> tag, %3%s is <code>, %4$s is a discount code, and %5$s is </code>.
                        printf(esc_html__( 'Upgrade to the Pro version today, and %1$ssave 15&#37;%2$s with the code %3$s%4$s%5$s at checkout.', 'say-what' ),'<em>','</em><sup>*</sup>','<code>', 'WPSAYWHAT', '</code>');
                        ?>
                    </strong>
                    <small class="text-muted">
                        <br/>
                        <sup>*</sup>
                        <a style="text-decoration:none;" rel="noopener noreferer" target="_blank" href="https://plugins.leewillis.co.uk/say-what-pro-discount-offer/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgradeterms"><?php esc_html_e( 'Terms apply', 'say-what' ); ?></a>
                    </small>
                </p>
                <p>
                    <a href="https://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgradebtn"
                       class="button" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Go Pro now &raquo;', 'say-what' ); ?></a>
                </p>
            </div>
        </div>
    </div>
</div>
