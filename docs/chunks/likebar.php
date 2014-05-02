{% set info = {
	'title' : 'cmcm.io',
	'url' : 'http://cmcm.io',
	'description' : 'Simple Content Manager for Designers'
}
%}

<ul class="likebar social-buttons cf">
       <li>
        <a href="http://www.facebook.com/sharer.php?u={{info.url}}&t={{info.title}}" class="socialite facebook-like" data-href="{{info.url}}" data-send="false" data-layout="button_count" data-width="60" data-show-faces="false" rel="nofollow" target="_blank"><span class="vhidden">Share on Facebook</span></a>
    </li>
    <li>
        <a href="http://twitter.com/share" class="socialite twitter-share" data-text="{{info.title}}" data-url="{{info.url}}" data-count="horizontal" rel="nofollow" target="_blank"><span class="vhidden">Share on Twitter</span></a>
    </li>
    <li>
        <a href="https://plus.google.com/share?url={{info.url}}" class="socialite googleplus-one" data-size="medium" data-href="{{info.url}}" rel="nofollow" target="_blank"><span class="vhidden">Share on Google+</span></a>
    </li>

</ul>
              