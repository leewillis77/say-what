<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h2><?php _e( 'Text changes', 'say-what' ); ?><a href="tools.php?page=say_what_admin&amp;say_what_action=addedit"
                                                     class="add-new-h2"><?php _e( 'Add New', 'say-what' ); ?></a></h2>
	<?php
	$list_table_instance = new SayWhatListTable( $this->settings );
	$list_table_instance->prepare_items();
	$list_table_instance->display();
	?>

    <div class="swp-upgrades-wrapper">
        <hr>
        <div class="swp-cpw">
            <div class="swp-img-container">
                <a href="https://ecologi.com/ademtisoftware?gift-trees&amp;r=ademtisoftware" target="_blank" rel="noopener noreferrer nofollow">
                    <img src="<?php echo esc_attr( plugins_url( 'say-what/img/cpw.png' ) ); ?>"
                         alt="We're a climate positive workforce">
                </a>
            </div>
            <div class="swp-intro-container">
                <p class="swp-intro">Using this plugin on your live site? Please buy the world some trees...</p>
                <div><a class="button" target="_blank" rel="noopener noreferrer nofollow"
                        href="https://ecologi.com/ademtisoftware?gift-trees&amp;r=ademtisoftware">Support this free plugin by planting trees</a></div>
            </div>
            <div class="swp-content-container">
                <p>It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our
                    temperatures from rising above 1.5C is to <a
                            href="https://www.bbc.co.uk/news/science-environment-48870920" target="_blank"
                            rel="noopener noreferrer nofollow">plant trees</a>.</p>
                <p>As a business, we already donate a percentage of our profits from premium plugins to global climate
                    change projects. You're free to use package free of charge, but if you do, please consider <a
                            target="_blank" rel="noopener noreferrer nofollow"
                            href="https://ecologi.com/ademtisoftware?gift-trees&amp;r=ademtisoftware">buying the world some trees</a> in return.
                    You’ll be creating employment for local families and restoring wildlife habitats.</p>
            </div>
        </div>
        <div class="saywhat-gopro">
            <div>
                <h2>Go pro today</h2>
                <hr>
                <p>
                    <a href="https://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade"
                       target="_blank" rel="noopener noreferrer">Say What? Pro</a> makes it even easier for you to
                    change strings:
                <ul class="ul-disc">
                    <li>
                        <a href="https://plugins.leewillis.co.uk/doc_post/string-discovery/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade"
                           target="_blank" rel="noopener noreferrer">String Discovery</a> and autocomplete - find the
                        strings you need without diving through code. Works with server-side and Javascript-rendered
                        strings.
                    </li>
                    <li>
                        <a href="https://plugins.leewillis.co.uk/doc_post/replace-words-across-whole-site/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade"
                           target="_blank" rel="noopener noreferrer">Wildcard string replacements</a> - replace
                        individual words, or fragments
                        across your whole site.
                    </li>
                    <li>
                        <a href="https://plugins.leewillis.co.uk/doc_post/multi-lingual-string-replacements/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade"
                           target="_blank" rel="noopener noreferrer">Multi-lingual support</a> - set different
                        replacements for different languages.</a></li>
                    <li>
                        <a href="https://plugins.leewillis.co.uk/doc_post/import-export-string-replacements/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgrade">Import
                            &amp; export</a> your strings between sites.
                    </li>
                    <li><em>Improved performance</em> using text-domain-specific filters.</li>
                </ul>
                </p>
                <p><strong>Upgrade to the Pro version today, and <em>save 15%</em> with the code <code>WPSAYWHAT</code>
                        at checkout.</strong></p>
                <p>
                <center>
                    <a href="https://plugins.leewillis.co.uk/downloads/say-what-pro/?utm_source=wporg&amp;utm_medium=plugin&amp;utm_campaign=saywhatproupgradebtn"
                       class="button button-primary" target="_blank" rel="noopener noreferrer">Go Pro now &raquo;</a>
                </center>
                </p>
            </div>
        </div>
    </div>
</div>
