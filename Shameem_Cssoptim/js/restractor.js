(function ($, MAGEURL) {
    var baseAjaxUrl = MAGEURL,
        baseResourceUrl = MAGEURL.replace(/index\.php\/$/, '');
    
    function no_comments(css) {
        return css.replace(/\/\*([\s\S]*?)\*\//g, '').replace(/\s+/g, ' ');
    }
    
    function get_used_css(css) {
        var outcss = [];
        var css_nocomments = css.replace(/\/\*([\s\S]*?)\*\//g, '').replace(/\s+/g, ' ');
        
        //var css_nobrakets = css_nocomments.replace(/\{([^{}]*?)\}/g, '[therules]');
        var rules = /[^{}]+\{[^{}]+\}/g;
        
        var match = rules.exec(css_nocomments);
        
        while(match !== null) {
            var rule = match[0];
            var selector = /[^{}]+/g.exec(rule);
            selector = selector[0].replace(/[:][^\s,]+/g, '');
            
            try {
                if ($(selector).length) {
                    outcss.push(rule);
                }
            } catch(e) {
                //probably a @font or something else
                outcss.push(rule);
            }
            match = rules.exec(css_nocomments);
        }
        
        return outcss.join("\n");
    }
    
    /**
     * tokenizes the css to divide media and non-media parts
     * @param {String} css
     * @returns {Object}
     */
    function tokenize_css(css) {
        css = css.replace(/\s+/g, ' ');
        var non_media = /(\}\s*}\s*)((?!@media)[^@])+/,
            non_media2 = /(((?!@media)(?!ç)[^{}])+\{[^{}]*\}[\s]+)+/,
            media = /(@media[^{}]+\{)([^{}]+\{[^{}]*\}[\s]+)*\}/,
            new_css, part, ret = {
                
            };
    
        ret.tokens = [];
        
//        //parse the ending of a media and the start of non-media rules
//        while (css.match(non_media)) {
//            var token_name = '@[[non_media_parts#' + (ret.tokens.length + 1) + ']]';
//            
//            new_css = css.replace(non_media, '$1' + token_name).split(token_name);
//            
//            part = css.substr(new_css[0].length, css.length - new_css.join("").length);
//            
//            css = new_css.join(token_name).replace(/\s+@[[]/, '@[');
//            
//            ret.tokens.push([token_name, part]);
//        }
        
        //parse the media rules
        while (css.match(media)) {
            var token_name = 'ç[[{}media_parts#' + (ret.tokens.length + 1) + ']]';
            
            new_css = css.replace(media, "$1" + token_name + "}").split(token_name);
            
            part = css.substr(new_css[0].length, css.length - new_css.join("").length);
            
            css = new_css.join(token_name).replace(/\s+@[[]/, 'ç[');
            
            ret.tokens.push([token_name, part]);
        }
        
        //parse the non-media rules
        while (css.match(non_media2)) {
            var token_name = 'ç[[{}non_media_parts2#' + (ret.tokens.length + 1) + ']]';
            
            new_css = css.replace(non_media2, token_name).split(token_name);
            
            part = css.substr(new_css[0].length, css.length - new_css.join("").length);
            
            css = new_css.join(token_name).replace(/\s+@[[]/, 'ç[');
            
            ret.tokens.push([token_name, part]);
        }
        
        ret.css = css;
        
        return ret;
    }
    
    function parse_css (css)
    {
        var tokenized = tokenize_css(css);
        
        for (var i = 0; i < tokenized.tokens.length; i++) {
            var token = tokenized.tokens[i][0],
                css = get_used_css(tokenized.tokens[i][1]);
        
            tokenized.css = tokenized.css.split(token).join(css);
        }
        
        return tokenized.css;
    }
    
    
    
    function read_css(url, css) {
        $.ajax({
            url: baseAjaxUrl + 'cssoptim',
            data: {
                css: parse_css(no_comments(css)),
                url: url
            },
            type: 'POST'
        });
        //console.log("/* url: " + url + "*/\n" + parse_css(no_comments(css)));
        //console.log(css_nobrakets);
    }
    
    function extractResources() {
        $('link[rel=stylesheet]').filter(':not([media=print])').each(function () {
            var url = $(this).attr('href');
            
            if (url.indexOf(baseResourceUrl + 'media/css') === 0) {
                $.ajax({
                    url: url,
                    success: read_css.bind(null, url)
                });
            }
        });
    }
    
    function replaceResource($style, data) {
        var $after = $('<style></style>').text(data);
        $style.after($after);
    }
    
    function replaceResources() {
        $('style[css-optim-data-url]').each(function () {
            $.ajax({
                url: $(this).attr('css-optim-data-url'),
                success: replaceResource.bind(null, $(this))
            });
        });
    }
    
    $(window).load(function () {
        setTimeout(replaceResources, 100);
        setTimeout(extractResources, 2000);
    });
    
})(jQuery.noConflict(), MAGEURL);