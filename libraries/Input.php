<?php
class Input {

    protected $ip_address = FALSE;

    protected $_allow_get_array = TRUE;

    protected $_standardize_newlines = FALSE;

    protected $_enable_xss = FALSE;

    protected $_enable_csrf = FALSE;

    protected $headers = array();

    protected $_raw_input_stream;

    protected $_input_stream;

    protected $security;
    protected $uni;

    public $charset = 'UTF-8';
    protected $_never_allowed_str =    array(
        'document.cookie' => '[removed]',
        '(document).cookie' => '[removed]',
        'document.write'  => '[removed]',
        '(document).write'  => '[removed]',
        '.parentNode'     => '[removed]',
        '.innerHTML'      => '[removed]',
        '-moz-binding'    => '[removed]',
        '<!--'            => '&lt;!--',
        '-->'             => '--&gt;',
        '<![CDATA['       => '&lt;![CDATA[',
        '<comment>'      => '&lt;comment&gt;',
        '<%'              => '&lt;&#37;'
    );
    protected $_never_allowed_regex = array(
        'javascript\s*:',
        '(\(?document\)?|\(?window\)?(\.document)?)\.(location|on\w*)',
        'expression\s*(\(|&\#40;)',
        'vbscript\s*:',
        'wscript\s*:',
        'jscript\s*:',
        'vbs\s*:',
        'Redirect\s+30\d',
        "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
    );

    public function __construct()
    {
        $this->_sanitize_globals();
    }

    protected function _fetch_from_array(&$array, $index = NULL, $xss_clean = NULL)
    {
        is_bool($xss_clean) OR $xss_clean = $this->_enable_xss;

        isset($index) OR $index = array_keys($array);

        if (is_array($index))
        {
            $output = array();
            foreach ($index as $key)
            {
                $output[$key] = $this->_fetch_from_array($array, $key, $xss_clean);
            }

            return $output;
        }

        if (isset($array[$index]))
        {
            $value = $array[$index];
        }
        elseif (($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1)
        {
            $value = $array;
            for ($i = 0; $i < $count; $i++)
            {
                $key = trim($matches[0][$i], '[]');
                if ($key === '')
                {
                    break;
                }

                if (isset($value[$key]))
                {
                    $value = $value[$key];
                }
                else
                {
                    return NULL;
                }
            }
        }
        else
        {
            return NULL;
        }

        return ($xss_clean === TRUE)
        ? $this->xss_clean($value)
        : $value;
    }

    public function get($index = NULL, $xss_clean = NULL)
    {
        return $this->_fetch_from_array($_GET, $index, $xss_clean);
    }

    public function post($index = NULL, $xss_clean = NULL)
    {
        return $this->_fetch_from_array($_POST, $index, $xss_clean);
    }

    public function post_get($index, $xss_clean = NULL)
    {
        return isset($_POST[$index])
        ? $this->post($index, $xss_clean)
        : $this->get($index, $xss_clean);
    }

    public function get_post($index, $xss_clean = NULL)
    {
        return isset($_GET[$index])
        ? $this->get($index, $xss_clean)
        : $this->post($index, $xss_clean);
    }

    public function cookie($index = NULL, $xss_clean = NULL)
    {
        return $this->_fetch_from_array($_COOKIE, $index, $xss_clean);
    }

    public function server($index, $xss_clean = NULL)
    {
        return $this->_fetch_from_array($_SERVER, $index, $xss_clean);
    }

    public function input_stream($index = NULL, $xss_clean = NULL)
    {
        if ( ! is_array($this->_input_stream))
        {
            parse_str($this->raw_input_stream, $this->_input_stream);
            is_array($this->_input_stream) OR $this->_input_stream = array();
        }

        return $this->_fetch_from_array($this->_input_stream, $index, $xss_clean);
    }

    public function set_cookie($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = NULL, $httponly = NULL)
    {
        if (is_array($name))
        {
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httponly', 'name') as $item)
            {
                if (isset($name[$item]))
                {
                    $$item = $name[$item];
                }
            }
        }

        if ($prefix === '' && config_item('cookie_prefix') !== '')
        {
            $prefix = config_item('cookie_prefix');
        }

        if ($domain == '' && config_item('cookie_domain') != '')
        {
            $domain = config_item('cookie_domain');
        }

        if ($path === '/' && config_item('cookie_path') !== '/')
        {
            $path = config_item('cookie_path');
        }

        $secure = ($secure === NULL && config_item('cookie_secure') !== NULL)
        ? (bool) config_item('cookie_secure')
        : (bool) $secure;

        $httponly = ($httponly === NULL && config_item('cookie_httponly') !== NULL)
        ? (bool) config_item('cookie_httponly')
        : (bool) $httponly;

        if ( ! is_numeric($expire))
        {
            $expire = time() - 86500;
        }
        else
        {
            $expire = ($expire > 0) ? time() + $expire : 0;
        }

        setcookie($prefix.$name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function ip_address()
    {
        if ($this->ip_address !== FALSE)
        {
            return $this->ip_address;
        }

        $proxy_ips = config_item('proxy_ips');
        if ( ! empty($proxy_ips) && ! is_array($proxy_ips))
        {
            $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));
        }

        $this->ip_address = $this->server('REMOTE_ADDR');

        if ($proxy_ips)
        {
            foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header)
            {
                if (($spoof = $this->server($header)) !== NULL)
                {

                    sscanf($spoof, '%[^,]', $spoof);

                    if ( ! $this->valid_ip($spoof))
                    {
                        $spoof = NULL;
                    }
                    else
                    {
                        break;
                    }
                }
            }

            if ($spoof)
            {
                for ($i = 0, $c = count($proxy_ips); $i < $c; $i++)
                {
                    if (strpos($proxy_ips[$i], '/') === FALSE)
                    {

                        if ($proxy_ips[$i] === $this->ip_address)
                        {
                            $this->ip_address = $spoof;
                            break;
                        }

                        continue;
                    }

                    isset($separator) OR $separator = $this->valid_ip($this->ip_address, 'ipv6') ? ':' : '.';

                    if (strpos($proxy_ips[$i], $separator) === FALSE)
                    {
                        continue;
                    }

                    if ( ! isset($ip, $sprintf))
                    {
                        if ($separator === ':')
                        {
                            $ip = explode(':',
                                str_replace('::',
                                    str_repeat(':', 9 - substr_count($this->ip_address, ':')),
                                    $this->ip_address
                                )
                            );

                            for ($j = 0; $j < 8; $j++)
                            {
                                $ip[$j] = intval($ip[$j], 16);
                            }

                            $sprintf = '%016b%016b%016b%016b%016b%016b%016b%016b';
                        }
                        else
                        {
                            $ip = explode('.', $this->ip_address);
                            $sprintf = '%08b%08b%08b%08b';
                        }

                        $ip = vsprintf($sprintf, $ip);
                    }

                    sscanf($proxy_ips[$i], '%[^/]/%d', $netaddr, $masklen);

                    if ($separator === ':')
                    {
                        $netaddr = explode(':', str_replace('::', str_repeat(':', 9 - substr_count($netaddr, ':')), $netaddr));
                        for ($j = 0; $j < 8; $j++)
                        {
                            $netaddr[$j] = intval($netaddr[$j], 16);
                        }
                    }
                    else
                    {
                        $netaddr = explode('.', $netaddr);
                    }

                    if (strncmp($ip, vsprintf($sprintf, $netaddr), $masklen) === 0)
                    {
                        $this->ip_address = $spoof;
                        break;
                    }
                }
            }
        }

        if ( ! $this->valid_ip($this->ip_address))
        {
            return $this->ip_address = '0.0.0.0';
        }

        return $this->ip_address;
    }

    public function valid_ip($ip, $which = '')
    {
        switch (strtolower($which))
        {
            case 'ipv4':
                $which = FILTER_FLAG_IPV4;
                break;
            case 'ipv6':
                $which = FILTER_FLAG_IPV6;
                break;
            default:
                $which = NULL;
                break;
        }

        return (bool) filter_var($ip, FILTER_VALIDATE_IP, $which);
    }

    public function user_agent($xss_clean = NULL)
    {
        return $this->_fetch_from_array($_SERVER, 'HTTP_USER_AGENT', $xss_clean);
    }

    protected function _sanitize_globals()
    {
        if ($this->_allow_get_array === FALSE)
        {
            $_GET = array();
        }
        elseif (is_array($_GET))
        {
            foreach ($_GET as $key => $val)
            {
                $_GET[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
        }

        if (is_array($_POST))
        {
            foreach ($_POST as $key => $val)
            {
                $_POST[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
        }

        if (is_array($_COOKIE))
        {

            unset(
                $_COOKIE['$Version'],
                $_COOKIE['$Path'],
                $_COOKIE['$Domain']
            );

            foreach ($_COOKIE as $key => $val)
            {
                if (($cookie_key = $this->_clean_input_keys($key)) !== FALSE)
                {
                    $_COOKIE[$cookie_key] = $this->_clean_input_data($val);
                }
                else
                {
                    unset($_COOKIE[$key]);
                }
            }
        }

        $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);
    }

    protected function _clean_input_data($str)
    {
        if (is_array($str))
        {
            $new_array = array();
            foreach (array_keys($str) as $key)
            {
                $new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($str[$key]);
            }
            return $new_array;
        }

        if ( ! @get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }

        $str = remove_invisible_characters($str, FALSE);

        if ($this->_standardize_newlines === TRUE)
        {
            return preg_replace('/(?:\r\n|[\r\n])/', PHP_EOL, $str);
        }

        return $str;
    }

    protected function _clean_input_keys($str, $fatal = TRUE)
    {
        if ( ! preg_match('/^[a-z0-9:_\/|-]+$/i', $str))
        {
            if ($fatal === TRUE)
            {
                return FALSE;
            }
            else
            {
                echo 'Disallowed Key Characters.';
                exit(7);
            }
        }

        return $str;
    }

    public function request_headers($xss_clean = FALSE)
    {
        if ( ! empty($this->headers))
        {
            return $this->_fetch_from_array($this->headers, NULL, $xss_clean);
        }

        if (function_exists('apache_request_headers'))
        {
            $this->headers = apache_request_headers();
        }
        else
        {
            isset($_SERVER['CONTENT_TYPE']) && $this->headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];

            foreach ($_SERVER as $key => $val)
            {
                if (sscanf($key, 'HTTP_%s', $header) === 1)
                {
                    $header = str_replace('_', ' ', strtolower($header));
                    $header = str_replace(' ', '-', ucwords($header));

                    $this->headers[$header] = $_SERVER[$key];
                }
            }
        }

        return $this->_fetch_from_array($this->headers, NULL, $xss_clean);
    }

    public function get_request_header($index, $xss_clean = FALSE)
    {
        static $headers;

        if ( ! isset($headers))
        {
            empty($this->headers) && $this->request_headers();
            foreach ($this->headers as $key => $value)
            {
                $headers[strtolower($key)] = $value;
            }
        }

        $index = strtolower($index);

        if ( ! isset($headers[$index]))
        {
            return NULL;
        }

        return ($xss_clean === TRUE)
        ? $this->security->xss_clean($headers[$index])
        : $headers[$index];
    }

    public function is_ajax_request()
    {
        return ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    public function method($upper = FALSE)
    {
        return ($upper)
        ? strtoupper($this->server('REQUEST_METHOD'))
        : strtolower($this->server('REQUEST_METHOD'));
    }

    public function __get($name)
    {
        if ($name === 'raw_input_stream')
        {
            isset($this->_raw_input_stream) OR $this->_raw_input_stream = file_get_contents('php://input');
            return $this->_raw_input_stream;
        }
        elseif ($name === 'ip_address')
        {
            return $this->ip_address;
        }
    }

    public function xss_clean($str, $is_image = FALSE)
    {
        if (is_array($str))
        {
            foreach ($str as $key => &$value)
            {
                $str[$key] = $this->xss_clean($value);
            }

            return $str;
        }

        $str = remove_invisible_characters($str);

        if (stripos($str, '%') !== false)
        {
            do
            {
                $oldstr = $str;
                $str = rawurldecode($str);
                $str = preg_replace_callback('#%(?:\s*[0-9a-f]){2,}#i', array($this, '_urldecodespaces'), $str);
            }
            while ($oldstr !== $str);
            unset($oldstr);
        }

        $str = preg_replace_callback("/[^a-z0-9>]+[a-z0-9]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);
        $str = preg_replace_callback('/<\w+.*/si', array($this, '_decode_entity'), $str);

        $str = remove_invisible_characters($str);

        $str = str_replace("\t", ' ', $str);

        $converted_string = $str;

        $str = $this->_do_never_allowed($str);

        if ($is_image === TRUE)
        {

            $str = preg_replace('/<\?(php)/i', '&lt;?\\1', $str);
        }
        else
        {
            $str = str_replace(array('<?', '?'.'>'), array('&lt;?', '?&gt;'), $str);
        }

        $words = array(
            'javascript', 'expression', 'vbscript', 'jscript', 'wscript',
            'vbs', 'script', 'base64', 'applet', 'alert', 'document',
            'write', 'cookie', 'window', 'confirm', 'prompt', 'eval'
        );

        foreach ($words as $word)
        {
            $word = implode('\s*', str_split($word)).'\s*';
            $str = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
        }

        /*
        * Remove disallowed Javascript in links or img tags
        * We used to do some version comparisons and use of stripos(),
        * but it is dog slow compared to these simplified non-capturing
        * preg_match(), especially if the pattern exists in the string
        *
        * Note: It was reported that not only space characters, but all in
        * the following pattern can be parsed as separators between a tag name
        * and its attributes: [\d\s"\'`;,\/\=\(\x00\x0B\x09\x0C]
        * ... however, remove_invisible_characters() above already strips the
        * hex-encoded ones, so we'll skip them below.
        */
        do
        {
            $original = $str;

            if (preg_match('/<a/i', $str))
            {
                $str = preg_replace_callback('#<a(?:rea)?[^a-z0-9>]+([^>]*?)(?:>|$)#si', array($this, '_js_link_removal'), $str);
            }

            if (preg_match('/<img/i', $str))
            {
                $str = preg_replace_callback('#<img[^a-z0-9]+([^>]*?)(?:\s?/?>|$)#si', array($this, '_js_img_removal'), $str);
            }

            if (preg_match('/script|xss/i', $str))
            {
                $str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
            }
        }
        while ($original !== $str);
        unset($original);

        $pattern = '#'
        .'<((?<slash>/*\s*)((?<tagName>[a-z0-9]+)(?=[^a-z0-9]|$)|.+)'
        .'[^\s\042\047a-z0-9>/=]*'
        .'(?<attributes>(?:[\s\042\047/=]*'
        .'[^\s\042\047>/=]+'

        .'(?:\s*='
        .'(?:[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*))'
        .')?'
        .')*)'
        .'[^>]*)(?<closeTag>\>)?#isS';

        do
        {
            $old_str = $str;
            $str = preg_replace_callback($pattern, array($this, '_sanitize_naughty_html'), $str);
        }
        while ($old_str !== $str);
        unset($old_str);


        $str = preg_replace(
            '#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',
            '\\1\\2&#40;\\3&#41;',
            $str
        );

        $str = preg_replace(
            '#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)`(.*?)`#si',
            '\\1\\2&#96;\\3&#96;',
            $str
        );

        $str = $this->_do_never_allowed($str);

        /*
        * Images are Handled in a Special Way
        * - Essentially, we want to know that after all of the character
        * conversion is done whether any unwanted, likely XSS, code was found.
        * If not, we return TRUE, as the image is clean.
        * However, if the string post-conversion does not matched the
        * string post-removal of XSS, then it fails, as there was unwanted XSS
        * code found and removed/changed during processing.
        */
        if ($is_image === TRUE)
        {
            return ($str === $converted_string);
        }

        return $str;
    }

    protected function _js_link_removal($match)
    {
        return str_replace(
            $match[1],
            preg_replace(
                '#href=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;|`|&\#96;)|javascript:|livescript:|mocha:|charset=|window\.|\(?document\)?\.|\.cookie|<script|<xss|d\s*a\s*t\s*a\s*:)#si',
                '',
                $this->_filter_attributes($match[1])
            ),
            $match[0]
        );
    }

    protected function _js_img_removal($match)
    {
        return str_replace(
            $match[1],
            preg_replace(
                '#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;|`|&\#96;)|javascript:|livescript:|mocha:|charset=|window\.|\(?document\)?\.|\.cookie|<script|<xss|base64\s*,)#si',
                '',
                $this->_filter_attributes($match[1])
            ),
            $match[0]
        );
    }

    protected function _compact_exploded_words($matches)
    {
        return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
    }

    protected function _urldecodespaces($matches)
    {
        $input    = $matches[0];
        $nospaces = preg_replace('#\s+#', '', $input);
        return ($nospaces === $input)
        ? $input
        : rawurldecode($nospaces);
    }

    protected function _sanitize_naughty_html($matches)
    {
        static $naughty_tags    = array(
            'alert', 'area', 'prompt', 'confirm', 'applet', 'audio', 'basefont', 'base', 'behavior', 'bgsound',
            'blink', 'body', 'embed', 'expression', 'form', 'frameset', 'frame', 'head', 'html', 'ilayer',
            'iframe', 'input', 'button', 'select', 'isindex', 'layer', 'link', 'meta', 'keygen', 'object',
            'plaintext', 'style', 'script', 'textarea', 'title', 'math', 'video', 'svg', 'xml', 'xss'
        );

        static $evil_attributes = array(
            'on\w+', 'style', 'xmlns', 'formaction', 'form', 'xlink:href', 'FSCommand', 'seekSegmentTime'
        );

        // First, escape unclosed tags
        if (empty($matches['closeTag']))
        {
            return '&lt;'.$matches[1];
        }
        // Is the element that we caught naughty? If so, escape it
        elseif (in_array(strtolower($matches['tagName']), $naughty_tags, TRUE))
        {
            return '&lt;'.$matches[1].'&gt;';
        }
        // For other tags, see if their attributes are "evil" and strip those
        elseif (isset($matches['attributes']))
        {
            // We'll store the already filtered attributes here
            $attributes = array();

            // Attribute-catching pattern
            $attributes_pattern = '#'
            .'(?<name>[^\s\042\047>/=]+)' // attribute characters
            // optional attribute-value
            .'(?:\s*=(?<value>[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*)))' // attribute-value separator
            .'#i';

            // Blacklist pattern for evil attribute names
            $is_evil_pattern = '#^('.implode('|', $evil_attributes).')$#i';

            // Each iteration filters a single attribute
            do
            {
                // Strip any non-alpha characters that may precede an attribute.
                // Browsers often parse these incorrectly and that has been a
                // of numerous XSS issues we've had.
                $matches['attributes'] = preg_replace('#^[^a-z]+#i', '', $matches['attributes']);

                if ( ! preg_match($attributes_pattern, $matches['attributes'], $attribute, PREG_OFFSET_CAPTURE))
                {
                    // No (valid) attribute found? Discard everything else inside the tag
                    break;
                }

                if (
                // Is it indeed an "evil" attribute?
                preg_match($is_evil_pattern, $attribute['name'][0])
                // Or does it have an equals sign, but no value and not quoted? Strip that too!
                OR (trim($attribute['value'][0]) === '')
                )
                {
                    $attributes[] = 'xss=removed';
                }
                else
                {
                    $attributes[] = $attribute[0][0];
                }

                $matches['attributes'] = substr($matches['attributes'], $attribute[0][1] + strlen($attribute[0][0]));
            }
            while ($matches['attributes'] !== '');

            $attributes = empty($attributes)
            ? ''
            : ' '.implode(' ', $attributes);
            return '<'.$matches['slash'].$matches['tagName'].$attributes.'>';
        }

        return $matches[0];
    }

    protected function _convert_attribute($match)
    {
        return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
    }

    protected function _do_never_allowed($str)
    {
        $str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);

        foreach ($this->_never_allowed_regex as $regex)
        {
            $str = preg_replace('#'.$regex.'#is', '[removed]', $str);
        }

        return $str;
    }

    protected function _decode_entity($match)
    {
        // Protect GET variables in URLs
        // 901119URL5918AMP18930PROTECT8198
        $match = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-/]+)|i', $this->xss_hash().'\\1=\\2', $match[0]);

        // Decode, then un-protect URL GET vars
        return str_replace(
            $this->xss_hash(),
            '&',
            $this->entity_decode($match, $this->charset)
        );
    }

    public function xss_hash()
    {
        if ($this->_xss_hash === NULL)
        {
            $this->_xss_hash = md5(uniqid(mt_rand(), TRUE));
        }

        return $this->_xss_hash;
    }

    public function entity_decode($str, $charset = NULL)
    {
        if (strpos($str, '&') === FALSE)
        {
            return $str;
        }

        static $_entities;

        isset($charset) OR $charset = $this->charset;
        $flag = ENT_COMPAT;

        if ( ! isset($_entities))
        {
            $_entities = array_map('strtolower', get_html_translation_table(HTML_ENTITIES, $flag, $charset));

            if ($flag === ENT_COMPAT)
            {
                $_entities[':'] = '&colon;';
                $_entities['('] = '&lpar;';
                $_entities[')'] = '&rpar;';
                $_entities["\n"] = '&NewLine;';
                $_entities["\t"] = '&Tab;';
            }
        }

        do
        {
            $str_compare = $str;

            if (preg_match_all('/&[a-z]{2,}(?![a-z;])/i', $str, $matches))
            {
                $replace = array();
                $matches = array_unique(array_map('strtolower', $matches[0]));
                foreach ($matches as &$match)
                {
                    if (($char = array_search($match.';', $_entities, TRUE)) !== FALSE)
                    {
                        $replace[$match] = $char;
                    }
                }

                $str = str_replace(array_keys($replace), array_values($replace), $str);
            }

            $str = html_entity_decode(
                preg_replace('/(&#(?:x0*[0-9a-f]{2,5}(?![0-9a-f;])|(?:0*\d{2,4}(?![0-9;]))))/iS', '$1;', $str),
                $flag,
                $charset
            );

            if ($flag === ENT_COMPAT)
            {
                $str = str_replace(array_values($_entities), array_keys($_entities), $str);
            }
        }
        while ($str_compare !== $str);
        return $str;
    }

}
?>
