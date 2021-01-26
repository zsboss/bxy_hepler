<?php
    
    declare(strict_types=1);
    
    namespace Bxy\Helper\Directory;
    
    
    class BrowserHelper
    {
        public static function getBrowser($useragent=null)
        {
            // check for most popular browsers first
            // unfortunately, that's IE. We also ignore Opera and Netscape 8
            // because they sometimes send msie agent
            $useragent=$useragent ?: $_SERVER['HTTP_USER_AGENT'];
            if (strpos($useragent, 'MSIE')!==false&&strpos($useragent, 'Opera')===false&&strpos(
                    $useragent,
                    'Netscape'
                )===false) {
                //deal with Blazer
                if (preg_match("/Blazer\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/", $useragent, $matches)) {
                    return ['blazer'=>$matches[1]];
                }
                //deal with IE
                if (preg_match("/MSIE ([0-9]{1,2}\.[0-9]{1,2})/", $useragent, $matches)) {
                    return ['ie'=>$matches[1]];
                }
            }elseif (strpos($useragent, 'IEMobile')!==false) {
                if (preg_match("/IEMobile\/([0-9]{1,2}\.[0-9]{1,2})/", $useragent, $matches)) {
                    return ['ie'=>$matches[1], 'ismobile'=>$matches[1]];
                }
            }elseif (strpos($useragent, 'Gecko')) {
                //deal with Gecko based
                if (strpos($useragent, 'Trident/7.0')!==false&&strpos($useragent, 'rv:11.0')!==false) {
                    return ['ie'=>11];
                } //if firefox
                elseif (preg_match("/Firefox\/([0-9]{1,2}\.[0-9]{1,2}(\.[0-9]{1,2})?)/", $useragent, $matches)) {
                    return ['firefox'=>$matches[1]];
                }
                
                //if Netscape (based on gecko)
                if (preg_match("/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/", $useragent, $matches)) {
                    return ['netscape'=>$matches[1]];
                }
                
                //check chrome before safari because chrome agent contains both
                if (preg_match("/Chrome\/([^\s]+)/", $useragent, $matches)) {
                    return ['chrome'=>$matches[1]];
                }
                
                //if Safari (based on gecko)
                if (preg_match("/Safari\/([0-9]{2,4}(\.[0-9])?)/", $useragent, $matches)) {
                    return ['safari'=>$matches[1]];
                }
                
                //if Galeon (based on gecko)
                if (preg_match("/Galeon\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/", $useragent, $matches)) {
                    return ['galeon'=>$matches[1]];
                }
                
                //if Konqueror (based on gecko)
                if (preg_match("/Konqueror\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/", $useragent, $matches)) {
                    return ['konqueror'=>$matches[1]];
                }
                
                // if Fennec (based on gecko)
                if (preg_match("/Fennec\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/", $useragent, $matches)) {
                    return ['fennec'=>$matches[1]];
                }
                
                // if Maemo (based on gecko)
                if (preg_match("/Maemo\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/", $useragent, $matches)) {
                    return ['maemo'=>$matches[1]];
                }
                
                //no specific Gecko found
                //return generic Gecko
                return ['Gecko based'=>true];
            }elseif (strpos($useragent, 'Opera')!==false) {
                //deal with Opera
                if (preg_match("/Opera[\/ ]([0-9]{1}\.[0-9]{1}([0-9])?)/", $useragent, $matches)) {
                    return ['opera'=>$matches[1]];
                }
            }elseif (strpos($useragent, 'Lynx')!==false) {
                //deal with Lynx
                if (preg_match("/Lynx\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/", $useragent, $matches)) {
                    return ['lynx'=>$matches[1]];
                }
            }elseif (strpos($useragent, 'Netscape')!==false) {
                //NN8 with IE string
                if (preg_match("/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/", $useragent, $matches)) {
                    return ['netscape'=>$matches[1]];
                }
            }else {
                //unrecognized, this should be less than 1% of browsers (not counting bots like google etc)!
                return 'unknown';
            }
        }
        
        public static function getplatform($useragent=null)
        {
            $useragent=$useragent ?: $_SERVER['HTTP_USER_AGENT'];
            $agent    =strtolower($useragent);
            $os       =[];
            if (false!==stripos($agent, "win")&&preg_match('/nt 5.1/', $agent)) {
                $os=['Windows'=>'XP'];
            }elseif (preg_match('win', $agent)&&preg_match('/nt 5.0/', $agent)) {
                $os=['Windows'=>'2000'];
            }elseif (preg_match('win', $agent)&&preg_match("/nt 5.2/i", $agent)) {
                $os=['Windows'=>'2003'];
            }elseif (false!==stripos($agent, "win")&&preg_match("/nt 6.0/i", $agent)) {
                $os=['Windows'=>'2008'];
            }elseif (false!==stripos($agent, "win")&&preg_match("/6.0/i", $agent)) {
                $os=['Windows'=>'vasta'];
            }elseif (false!==stripos($agent, "win")&&preg_match("/6.1/i", $agent)) {
                $os=['Windows'=>'7'];
            }elseif (false!==stripos($agent, "win")&&preg_match("/6.2/i", $agent)) {
                $os=['Windows'=>'8'];
            }elseif (false!==stripos($agent, "win")&&preg_match("/nt 6.3/i", $agent)) {
                $os=['Windows'=>'8.1'];
            }elseif (false!==stripos($agent, "win")&&false!==stripos($agent, "nt")) {
                $os=['Windows'=>'nt'];
            }elseif (false!==stripos($agent, "ipad")&&preg_match('/mac os/i', $agent)) {
                $os=['iPad'=>true];
            }elseif (false!==stripos($agent, "iphone")&&preg_match('/mac os/i', $agent)) {
                $os=['iPhone'=>true];
            }elseif (false!==stripos($agent, "ipod")&&preg_match('/mac os/i', $agent)) {
                $os=['iPod'=>true];
            }elseif (false!==stripos($agent, "linux")&&false!==stripos($agent, "Android")) {
                $os=['Android'=>true];
            }elseif (false!==stripos($agent, "linux")) {
                $os=['Linux'=>true];
            }elseif (false!==stripos($agent, "unix")) {
                $os=['Unix'=>true];
            }elseif (false!==stripos($agent, "Mac")&&false!==stripos($agent, "Macintosh")) {
                $os=['Macintosh'=>true];
            }
            return $os;
        }
       public static function is_mobile()
        {
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            if (preg_match("/WindowsWechat/i", $agent)) {
                return false;//return 'WindowsWechat';pc微信客户端打开pc版
            }
            elseif (preg_match("/macintosh/i", $agent) && preg_match("/MicroMessenger/i", $agent)) {
                return false;//苹果电脑系统pc端
            }
            elseif (preg_match("/MicroMessenger/i", $agent)) {
                return 'wechat';
            }
            elseif (preg_match("/iphone/i", $agent) && preg_match("/mac os/i", $agent)) {
                return 'iPhone';
            } elseif (preg_match("/ipod/i", $agent) && preg_match("/mac os/i", $agent)) {
                return 'iPod';
            } elseif (preg_match("/linux/i", $agent) && preg_match("/Android/i", $agent)) {
                return 'Android';
            }
            return false;
        }
    }