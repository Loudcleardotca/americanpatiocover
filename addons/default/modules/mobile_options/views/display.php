<ul id="social_media">
    <link href="{{ url:site }}/addons/shared_addons/widgets/social_media/css/social_media.css" type="text/css" rel="stylesheet" />
    <?php
		if(isset($options['audioboo'])){ if($options['audioboo'] == 'yes'){ echo '<li><a class="social_link_audioboo" href="'.$audioboo_url.'" title="audioboo"></a></li>'."\n"; }}
		if(isset($options['bebo'])){ if($options['bebo'] == 'yes'){ echo '<li><a class="social_link_bebo" href="'.$bebo_url.'" title="bebo"></a></li>'."\n"; }}
		if(isset($options['behance'])){ if($options['behance'] == 'yes'){ echo '<li><a class="social_link_behance" href="'.$behance_url.'" title="behance"></a></li>'."\n"; }}
		if(isset($options['blogger'])){ if($options['blogger'] == 'yes'){ echo '<li><a class="social_link_blogger" href="'.$blogger_url.'" title="blogger"></a></li>'."\n"; }}
		if(isset($options['buzz'])){ if($options['buzz'] == 'yes'){ echo '<li><a class="social_link_buzz" href="'.$buzz_url.'" title="buzz"></a></li>'."\n"; }}
		if(isset($options['creativecommons'])){ if($options['creativecommons'] == 'yes'){ echo '<li><a class="social_link_creativecommons" href="'.$creativecommons_url.'" title="creativecommons"></a></li>'."\n"; }}
		if(isset($options['dailybooth'])){ if($options['dailybooth'] == 'yes'){ echo '<li><a class="social_link_dailybooth" href="'.$dailybooth_url.'" title="dailybooth"></a></li>'."\n"; }}
		if(isset($options['delicious'])){ if($options['delicious'] == 'yes'){ echo '<li><a class="social_link_delicious" href="'.$delicious_url.'" title="delicious"></a></li>'."\n"; }}
		if(isset($options['designfloat'])){ if($options['designfloat'] == 'yes'){ echo '<li><a class="social_link_designfloat" href="'.$designfloat_url.'" title="designfloat"></a></li>'."\n"; }}
		if(isset($options['deviantart'])){ if($options['deviantart'] == 'yes'){ echo '<li><a class="social_link_deviantart" href="'.$deviantart_url.'" title="deviantart"></a></li>'."\n"; }}
		if(isset($options['digg'])){ if($options['digg'] == 'yes'){ echo '<li><a class="social_link_digg" href="'.$digg_url.'" title="digg"></a></li>'."\n"; }}
		if(isset($options['dopplr'])){ if($options['dopplr'] == 'yes'){ echo '<li><a class="social_link_dopplr" href="'.$dopplr_url.'" title="dopplr"></a></li>'."\n"; }}
		if(isset($options['dribbble'])){ if($options['dribbble'] == 'yes'){ echo '<li><a class="social_link_dribbble" href="'.$dribbble_url.'" title="dribbble"></a></li>'."\n"; }}
		if(isset($options['email'])){ if($options['email'] == 'yes'){ echo '<li><a class="social_link_email" href="'.$email_url.'" title="email"></a></li>'."\n"; }}
		if(isset($options['ember'])){ if($options['ember'] == 'yes'){ echo '<li><a class="social_link_ember" href="'.$ember_url.'" title="ember"></a></li>'."\n"; }}
		if(isset($options['facebook'])){ if($options['facebook'] == 'yes'){ echo '<li><a class="social_link_facebook" href="'.$facebook_url.'" title="facebook"></a></li>'."\n"; }}
		if(isset($options['flickr'])){ if($options['flickr'] == 'yes'){ echo '<li><a class="social_link_flickr" href="'.$flickr_url.'" title="flickr"></a></li>'."\n"; }}
		if(isset($options['forrst'])){ if($options['forrst'] == 'yes'){ echo '<li><a class="social_link_forrst" href="'.$forrst_url.'" title="forrst"></a></li>'."\n"; }}
		if(isset($options['friendfeed'])){ if($options['friendfeed'] == 'yes'){ echo '<li><a class="social_link_friendfeed" href="'.$friendfeed_url.'" title="friendfeed"></a></li>'."\n"; }}
		if(isset($options['google'])){ if($options['google'] == 'yes'){ echo '<li><a class="social_link_google" href="'.$google_url.'" title="google"></a></li>'."\n"; }}
		if(isset($options['gowalla'])){ if($options['gowalla'] == 'yes'){ echo '<li><a class="social_link_gowalla" href="'.$gowalla_url.'" title="gowalla"></a></li>'."\n"; }}
		if(isset($options['gplus'])){ if($options['gplus'] == 'yes'){ echo '<li><a class="social_link_gplus" href="'.$gplus_url.'" title="gplus"></a></li>'."\n"; }}
		if(isset($options['grooveshark'])){ if($options['grooveshark'] == 'yes'){ echo '<li><a class="social_link_grooveshark" href="'.$grooveshark_url.'" title="grooveshark"></a></li>'."\n"; }}
		if(isset($options['hyves'])){ if($options['hyves'] == 'yes'){ echo '<li><a class="social_link_hyves" href="'.$hyves_url.'" title="hyves"></a></li>'."\n"; }}
		if(isset($options['lastfm'])){ if($options['lastfm'] == 'yes'){ echo '<li><a class="social_link_lastfm" href="'.$lastfm_url.'" title="lastfm"></a></li>'."\n"; }}
		if(isset($options['linkedin'])){ if($options['linkedin'] == 'yes'){ echo '<li><a class="social_link_linkedin" href="'.$linkedin_url.'" title="linkedin"></a></li>'."\n"; }}
		if(isset($options['livejournal'])){ if($options['livejournal'] == 'yes'){ echo '<li><a class="social_link_livejournal" href="'.$livejournal_url.'" title="livejournal"></a></li>'."\n"; }}
		if(isset($options['lockerz'])){ if($options['lockerz'] == 'yes'){ echo '<li><a class="social_link_lockerz" href="'.$lockerz_url.'" title="lockerz"></a></li>'."\n"; }}
		if(isset($options['megavideo'])){ if($options['megavideo'] == 'yes'){ echo '<li><a class="social_link_megavideo" href="'.$megavideo_url.'" title="megavideo"></a></li>'."\n"; }}
		if(isset($options['myspace'])){ if($options['myspace'] == 'yes'){ echo '<li><a class="social_link_myspace" href="'.$myspace_url.'" title="myspace"></a></li>'."\n"; }}
		if(isset($options['odinict'])){ if($options['odinict'] == 'yes'){ echo '<li><a class="social_link_odinict" href="'.$odinict_url.'" title="odinict"></a></li>'."\n"; }}
		if(isset($options['piano'])){ if($options['piano'] == 'yes'){ echo '<li><a class="social_link_piano" href="'.$piano_url.'" title="piano"></a></li>'."\n"; }}
		if(isset($options['playfire'])){ if($options['playfire'] == 'yes'){ echo '<li><a class="social_link_playfire" href="'.$playfire_url.'" title="playfire"></a></li>'."\n"; }}
		if(isset($options['playstation'])){ if($options['playstation'] == 'yes'){ echo '<li><a class="social_link_playstation" href="'.$playstation_url.'" title="playstation"></a></li>'."\n"; }}
		if(isset($options['reddit'])){ if($options['reddit'] == 'yes'){ echo '<li><a class="social_link_reddit" href="'.$reddit_url.'" title="reddit"></a></li>'."\n"; }}
		if(isset($options['rss'])){ if($options['rss'] == 'yes'){ echo '<li><a class="social_link_rss" href="'.$rss_url.'" title="rss"></a></li>'."\n"; }}
		if(isset($options['skype'])){ if($options['skype'] == 'yes'){ echo '<li><a class="social_link_skype" href="'.$skype_url.'" title="skype"></a></li>'."\n"; }}
		if(isset($options['socialvibe'])){ if($options['socialvibe'] == 'yes'){ echo '<li><a class="social_link_socialvibe" href="'.$socialvibe_url.'" title="socialvibe"></a></li>'."\n"; }}
		if(isset($options['soundcloud'])){ if($options['soundcloud'] == 'yes'){ echo '<li><a class="social_link_soundcloud" href="'.$soundcloud_url.'" title="soundcloud"></a></li>'."\n"; }}
		if(isset($options['spotify'])){ if($options['spotify'] == 'yes'){ echo '<li><a class="social_link_spotify" href="'.$spotify_url.'" title="spotify"></a></li>'."\n"; }}
		if(isset($options['steam'])){ if($options['steam'] == 'yes'){ echo '<li><a class="social_link_steam" href="'.$steam_url.'" title="steam"></a></li>'."\n"; }}
		if(isset($options['stumbleupon'])){ if($options['stumbleupon'] == 'yes'){ echo '<li><a class="social_link_stumbleupon" href="'.$stumbleupon_url.'" title="stumbleupon"></a></li>'."\n"; }}
		if(isset($options['technorati'])){ if($options['technorati'] == 'yes'){ echo '<li><a class="social_link_technorati" href="'.$technorati_url.'" title="technorati"></a></li>'."\n"; }}
		if(isset($options['tumblr'])){ if($options['tumblr'] == 'yes'){ echo '<li><a class="social_link_tumblr" href="'.$tumblr_url.'" title="tumblr"></a></li>'."\n"; }}
		if(isset($options['twitpic'])){ if($options['twitpic'] == 'yes'){ echo '<li><a class="social_link_twitpic" href="'.$twitpic_url.'" title="twitpic"></a></li>'."\n"; }}
		if(isset($options['twitter'])){ if($options['twitter'] == 'yes'){ echo '<li><a class="social_link_twitter" href="'.$twitter_url.'" title="twitter"></a></li>'."\n"; }}
		if(isset($options['typepad'])){ if($options['typepad'] == 'yes'){ echo '<li><a class="social_link_typepad" href="'.$typepad_url.'" title="typepad"></a></li>'."\n"; }}
		if(isset($options['vimeo'])){ if($options['vimeo'] == 'yes'){ echo '<li><a class="social_link_vimeo" href="'.$vimeo_url.'" title="vimeo"></a></li>'."\n"; }}
		if(isset($options['wakoopa'])){ if($options['wakoopa'] == 'yes'){ echo '<li><a class="social_link_wakoopa" href="'.$wakoopa_url.'" title="wakoopa"></a></li>'."\n"; }}
		if(isset($options['wordpress'])){ if($options['wordpress'] == 'yes'){ echo '<li><a class="social_link_wordpress" href="'.$wordpress_url.'" title="wordpress"></a></li>'."\n"; }}
		if(isset($options['xing'])){ if($options['xing'] == 'yes'){ echo '<li><a class="social_link_xing" href="'.$xing_url.'" title="xing"></a></li>'."\n"; }}
		if(isset($options['yahoo'])){ if($options['yahoo'] == 'yes'){ echo '<li><a class="social_link_yahoo" href="'.$yahoo_url.'" title="yahoo"></a></li>'."\n"; }}
		if(isset($options['youtube'])){ if($options['youtube'] == 'yes'){ echo '<li><a class="social_link_youtube" href="'.$youtube_url.'" title="youtube"></a></li>'."\n"; }}
    ?>
</ul>
