import { is_hex, is_number, replace } from '@/vendor/services/events';





    let $css_all: string[] = [];
    let $responsivooo: string[] = [
        'dn_', 'dnn_', 'db_', 'dib_', 'dii_', 'w100p_', 'h100p_', 'w-a_', 'h-a_', 'p0_', 'pt0_', 'pb0_', 'pl0_', 'pr0_',
        'm0_', 'mt0_', 'mb0_', 'ml0_', 'mr0_', 'm-a_', 'fln_', 'fll_', 'flr_', 'posa_', 'posf_', 'poss_', 'tac_', 'tal_',
        'tar_', 'jc_', 'jr_', 'bd0_', 'bg0_'
    ];
    let $responsivooo1: string[] = [
        'display:none', 'display:block', 'display:block', 'display:inline-block', 'display:inline-block', 'width: 100%',
        'height: 100%', 'width: auto', 'height: auto', 'padding: 0', 'padding-top: 0', 'padding-bottom: 0',
        'padding-left: 0', 'padding-right: 0', 'margin: 0', 'margin-top: 0', 'margin-bottom: 0', 'margin-left: 0',
        'margin-right: 0', 'margin: auto', 'float: none', 'float: left', 'float: right', 'position: absolute',
        'position: fixed', 'position: static', 'text-align: center', 'text-align: left', 'text-align: right',
        'justify-content: center', 'justify-content: flex-end', 'border:0', 'background: none'
    ];


    // CSS
        export function css($classe_pai_: string = '')
        {
            //load();
            setTimeout(() => {
                create_css($classe_pai_);
            }, 50);
        }
        function create_css($classe_pai_: string)
        {
            let $classe_all: string = '';
            let $classe_pai: string = classe_pai($classe_pai_ ? $classe_pai_+' *' : '*');

            var $css: Array<string> = new Array();
            let $x: number = 0;
            let $y: number = 0;

            document.querySelectorAll($classe_pai).forEach($value => {
                let $classe = $value.getAttribute('class');
                if ($classe){
                    let $array = $classe.split(" ");
                    $array.map(function($val, $key){
                        $val = $val.trim();
                        if (verificando_classes($val)){
                            if ($css.indexOf($val) < 0){
                                $css[$x] = $val;
                                $x++;
                            }
                        }
                        // RESPONSIVO
                            $responsivooo.map(function($val1, $key1){
                                $val = $val.trim();
                                if ($val.match($val1)){
                                    if ($css.indexOf($val) < 0){
                                        $css[$x] = $val;
                                        $x++;
                                    }
                                }
                            });
                        // RESPONSIVO
                    });	
                    $y++;
                    $classe_all += $classe+' || ';
                }
            });
            // $css.sort();


            var $css_final: string = '';
            var $css_final_media: Array<string> = [];

            /*
            * w100
            * h100
            * p50 pt50 pb50 pl50 pr50
            * m50 mt50 mb50 ml50 mr50
            * fz40
            * br10
            */

            $css.map(function($val, $key){
                let exception_default = [`w150`, `w200`, `h150`, `h200`];
                let exception_width_height = [`w100p`, `w100p_1`, `h100p`, `h100vh`, `h100vw`, `min-w100p`, `max-w100p`, `min-h100p`, `max-h100p`, `min-h100vh`, `max-h100vh`];
                let exception_border_radius = [`br1`, `br2`, `br3`, `br4`, `br5`, `br6`, `br7`, `br8`, `br9`, `br10`, `br20`, `br40`, `br50`, `br50p`];
                let exception_color = [`c_f4f4f4`, `c_f7f7f7`, `c_000`, `c_111`, `c_222`, `c_333`, `c_444`, `c_555`, `c_666`, `c_777`, `c_888`, `c_999`, `c_aaa`, `c_bbb`, `c_ccc`, `c_ddd`, `c_eee`, `c_fff`];
                let exception_bd = [`bd_f4f4f4`, `bd_f7f7f7`, `bd_000`, `bd_111`, `bd_222`, `bd_333`, `bd_444`, `bd_555`, `bd_666`, `bd_777`, `bd_888`, `bd_999`, `bd_aaa`, `bd_bbb`, `bd_ccc`, `bd_ddd`, `bd_eee`, `bd_fff`];
                let exception_back = [`bg_f4f4f4`, `bg_f7f7f7`, `bg_000`, `bg_111`, `bg_222`, `bg_333`, `bg_444`, `bg_555`, `bg_666`, `bg_777`, `bg_888`, `bg_999`, `bg_aaa`, `bg_bbb`, `bg_ccc`, `bg_ddd`, `bg_eee`, `bg_fff`];

                let exception = exception_default.concat(exception_width_height).concat(exception_border_radius).concat(exception_color).concat(exception_bd).concat(exception_back);

                if (!exception.includes($val)){
                    let $criando_css = criando_css($val);

                    if ($criando_css.match("@media")){
                        $css_final_media.push($criando_css);
                    } else {
                        if ($css_all.indexOf($criando_css) < 0){
                            $css_final += $criando_css;
                            $css_all.push($criando_css);
                        }
                    }
                }
            });	

            if ($css_final){
                let $style = `<style class="${nome_class_style($classe_pai_)} desk">${$css_final}</style>`;
                document.head.insertAdjacentHTML('beforeend', $style);
            }


            // MEDIA (RESPONSIVO)
                var $css_final_media_1: Array<string> = [];
                var $css_final_media_2: Array<string> = [];

                $css_final_media.map(function($val, $key){
                    let $ex = $val.split("max-width:");
                    $ex = $ex[1].split("px){");
                    let $ex_1 = parseInt($ex[0]);
                    if ($ex_1 >= 1000){
                        $css_final_media_1.push($val);
                    } else {
                        $css_final_media_2.push($val);
                    }
                });
                $css_final_media_1.sort(); $css_final_media_1.reverse();
                $css_final_media_2.sort(); $css_final_media_2.reverse();

                let $css_final_media_3: string = '';
                $css_final_media_1.map(function($val, $key){
                    if ($css_all.indexOf($val) < 0){
                        $css_final_media_3 += $val;
                        $css_all.push($val);
                    }
                });
                $css_final_media_2.map(function($val, $key){
                    if ($css_all.indexOf($val) < 0){
                        $css_final_media_3 += $val;
                        $css_all.push($val);
                    }
                });
                if ($css_final_media_3){
                    let $style = `<style class="${nome_class_style($classe_pai_)} mobile">${$css_final_media_3}</style>`;
                    document.head.insertAdjacentHTML('beforeend', $style); // mobile para w, h e fz
                }
            // MEDIA (RESPONSIVO)

            //load_close();

            // CRIANDO ARQUIVO CACHE
                //try {
                    //if ($CRIAR_CACHE_CSS){
                        //ajax('Models/padrao/cache_js.php', { style: `${$css_final} ${$css_final_media}` }, function(){ load_close(); });
                    //}
                //} catch(e){ }
            // CRIANDO ARQUIVO CACHE
        }
    // CSS







    // NOME DA CLASS DO STYLE
        function nome_class_style($classe_pai_: string): string
        {
            let $return = '';
            $return = `style_${$classe_pai_}`;
            $return = $return ? $return : 'all';
            $return = replace(' ', '__', $return);
            $return = replace('.', '', $return);
            $return = replace('#', '', $return);
            return $return;
        }
    // NOME DA CLASS DO STYLE







    // IMPORTANT
        function css_important($ex: Array<string>): string
        {
            let $return = '';
            for (let $i=0; $i < 10; $i++){ 
                $return += ($ex[$i] && $ex[$i]=='i') ? ' !important' : '';
            }
            return $return;
        }
    // IMPORTANT







    // CRIANDOOO
        function criando_css($value: string): string
        {
            let $attr = '';
            let $val = '';
            let $style = '';
            let $outros = '';
            let $outros1 = $value.match("hover_") ? ':hover' : '';
            let $media = '';

            // BACKGROUND
                if ($value.match("bg_")){
                    $attr = 'background';
                    let $ex = $value.split("_");
                    if ($ex[1] && $ex[1].length >= 3){
                        if ($ex[1] == 'hover' && $ex[2]){
                            $val = '#'+$ex[2]+css_important($ex);
                        } else 	if ($ex[2] && $ex[2] != 'i'){
                            $val = 'filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="'+"#"+$ex[1]+'", endColorstr="'+"#"+$ex[2]+'");  background:-moz-linear-gradient(top, '+"#"+$ex[1]+', '+"#"+$ex[2]+'); background:-webkit-gradient(linear, left top, left bottom, from('+"#"+$ex[1]+'), to('+"#"+$ex[2]+'));'; 
                        } else if (is_hex($ex[1]) || is_number($ex[1])) {
                            $val = '#'+$ex[1]+css_important($ex);
                        }
                    }
                }
            // BACKGROUND

            // BORDER
                else if ($value.match("bd_") || $value.match("bdt_") || $value.match("bdb_") || $value.match("bdr_") || $value.match("bdl_")){
                    $attr = 'border';
                    if ($value.match('bdt_'))		$attr = 'border-top';
                    else if ($value.match('bdb_'))	$attr = 'border-bottom';
                    else if ($value.match('bdl_'))	$attr = 'border-left';
                    else if ($value.match('bdr_'))	$attr = 'border-right';
                    let $ex = $value.split("_");
                    if ($ex[1] && $ex[1].length >= 3){
                        if ($ex[1] == 'hover' && $ex[2]){
                            $val = '1px solid #'+$ex[2]+css_important($ex);
                        } else if (is_hex($ex[1]) || is_number($ex[1])) {
                            $val = '1px solid #'+$ex[1]+css_important($ex);
                        }
                    }
                }
            // BORDER

            // BORDER WIDTH
                else if ($value.match("bdw")){
                    $attr = 'border-width';
                    let $ex = $value.split("bdw");
                    if ($ex[1]){
                        $val = $ex[1]+'px !important';
                    }
                }
            // BORDER WIDTH

            // COLOR
                else if ($value.match("c_")){
                    $attr = 'color';
                    $outros = 'a.'+$value+$outros1+', ';
                    let $ex = $value.split("_");
                    if ($ex[1] && $ex[1].length >= 3){
                        if ($ex[1] == 'hover' && $ex[2]){
                            $val = '#'+$ex[2]+css_important($ex);
                        } else if (is_hex($ex[1]) || is_number($ex[1])) {
                            $val = '#'+$ex[1]+css_important($ex);
                        }
                    }
                }
            // COLOR

            // MIN, MAX, W E H
                else if ($value.match("min-w") || $value.match("max-w") || $value.match("min-h") || $value.match("max-h")){
                    let $attr1 = '';
                    if ($value.match('min-w')){
                        $attr = 'min-width';
                        $attr1 = 'min-w';
                    } else if ($value.match('max-w')){
                        $attr = 'max-width';
                        $attr1 = 'max-w';
                    } else if ($value.match('min-h')){
                        $attr = 'min-height';
                        $attr1 = 'min-h';
                    } else if ($value.match('max-h')){
                        $attr = 'max-height';
                        $attr1 = 'max-h';
                    }
                    let $ex = $value.split($attr1);
                    if ($ex[1]){
                        $val = $ex[1]+'px !important';
                    }
                }
            // MIN, MAX, W E H


            // CALC
                else if (($value.match("calc-") || $value.match("calch-")) && !$value.match("max-") && !$value.match("min-")){ // calc-100p_2
                    if ($value.match('calch-')){ // .calch-100p_20
                        $attr = 'height';
                        let $ex = $value.split("calch-");
                        if ($ex[1]){
                            let $ex_1 = $ex[1].split("_");
                            $val = `-webkit-calc(${replace('p', '%', $ex_1[0])} - ${$ex_1[1]}px) !important;height:-moz-calc(${replace('p', '%', $ex_1[0])} - ${$ex_1[1]}px) !important;height:calc(${replace('p', '%', $ex_1[0])} - ${$ex_1[1]}px) !important`;
                        }

                    } else { // .calc-100p_20
                        $attr = 'width';
                        let $ex = $value.split("calc-");
                        if ($ex[1]){
                            let $ex_1 = $ex[1].split("_");
                            $val = `-webkit-calc(${replace('p', '%', $ex_1[0])} - ${$ex_1[1]}px) !important;width:-moz-calc(${replace('p', '%', $ex_1[0])} - ${$ex_1[1]}px) !important;width:calc(${replace('p', '%', $ex_1[0])} - ${$ex_1[1]}px) !important`;
                        }
                    }
                }
            // CALC


            // WIDTH
                else if ($value.match("w1") || $value.match("w2") || $value.match("w3") || $value.match("w4") || $value.match("w5") || $value.match("w6") || $value.match("w7") || $value.match("w8") || $value.match("w9") || $value.match("w0")){
                    // MEDIA
                        let $value_ = $value;

                        let $style_media = '';
                        if ($value.match('_')){
                            let $ex = $value.split("_");
                            $style_media = $ex[1];
                            $value = $ex[0];
                        }
                    // MEDIA

                    if ($value.match('p')){
                        let $v = $value;
                        $v = replace('w', '', $v);
                        $v = replace('p', '%', $v);
                        $style = '.'+$value_+'{width:'+$v+' !important}';
                    } else {
                        let $v__ = $value;
                        $v__ = replace('w', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 100 || $style_media){ // w > 100
                            $style = '.'+$value_+'{width:'+$v+'px !important}';
                        }
                    }

                    // MEDIA
                        if ($style_media){
                            $style = '@media screen AND (max-width:'+(parseInt($style_media)-1)+'px){ '+$style+' }';
                        }
                    // MEDIA
                }
            // WIDTH

            // HEIGHT E LINE-HEIGHT
                else if ($value.match("h1") || $value.match("h2") || $value.match("h3") || $value.match("h4") || $value.match("h5") || $value.match("h6") || $value.match("h7") || $value.match("h8") || $value.match("h9") || $value.match("h0")){
                    // MEDIA
                        let $value_ = $value;

                        let $style_media = '';
                        if ($value.match('_')){
                            let $ex = $value.split("_");
                            $style_media = $ex[1];
                            $value = $ex[0];
                        }
                    // MEDIA

                    if ($value.match('lh')){
                        let $v__ = $value;
                        $v__ = replace('lh', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 40 || $style_media){ // lh > 40
                            $style = '.'+$value_+'{line-height:'+$v+'px !important}';
                        }
                    } else if ($value.match('p')){
                        let $v = $value;
                        $v = replace('h', '', $v);
                        $v = replace('p', '%', $v);
                        $style = '.'+$value_+'{height:'+$v+' !important}';
                    } else {
                        let $v__ = $value;
                        $v__ = replace('h', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 100 || $style_media){ // h > 100
                            $style = '.'+$value_+'{height:'+$v+'px !important}';
                        }
                    }

                    // MEDIA
                        if ($style_media){
                            $style = '@media screen AND (max-width:'+(parseInt($style_media)-1)+'px){ '+$style+' }';
                        }
                    // MEDIA
                }
            // HEIGHT

            // FONT-SIZE
                else if ($value.match("fz1") || $value.match("fz2") || $value.match("fz3") || $value.match("fz4") || $value.match("fz5") || $value.match("fz6") || $value.match("fz7") || $value.match("fz8") || $value.match("fz9") || $value.match("fz0")){
                    // MEDIA
                        let $value_ = $value;

                        let $style_media = '';
                        if ($value.match('_')){
                            let $ex = $value.split("_");
                            $style_media = $ex[1];
                            $value = $ex[0];
                        }
                    // MEDIA

                    if ($value.match('i')){
                        let $v = $value;
                        $v = replace('fz', '', $v);
                        $v = replace('i', '', $v);
                        let $x = parseInt($v);
                        $style = '.'+$value_+'{font-size:'+$x+'px !important;line-height:'+($x+2)+'px !important}';
                    } else {
                        let $v__ = $value;
                        $v__ = replace('fz', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 40 || $style_media){ // fz > 40
                            $style = '.'+$value_+'{font-size:'+$v+'px;line-height:'+($v+2)+'px}';
                        }
                    }

                    // MEDIA
                        if ($style_media){
                            $style = '@media screen AND (max-width:'+(parseInt($style_media)-1)+'px){ '+$style+' }';
                        }
                    // MEDIA
                }
            // FONT-SIZE

            // PADDING
                else if ($value.match("p1") || $value.match("p2") || $value.match("p3") || $value.match("p4") || $value.match("p5") || $value.match("p6") || $value.match("p7") || $value.match("p8") || $value.match("p9") || $value.match("p0") || $value.match("pt") || $value.match("pb") || $value.match("pl") || $value.match("pr")){
                    // MEDIA
                        let $value_ = $value;

                        let $style_media = '';
                        if ($value.match('_')){
                            let $ex = $value.split("_");
                            $style_media = $ex[1];
                            $value = $ex[0];
                        }
                    // MEDIA

                    if ($value.match('pt')){
                        let $v__ = $value;
                        $v__ = replace('pt', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // pt > 50
                            $style = '.'+$value_+'{padding-top:'+$v+'px !important}';
                        }
                    } else if ($value.match('pb')){
                        let $v__ = $value;
                        $v__ = replace('pb', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // pb > 50
                            $style = '.'+$value_+'{padding-bottom:'+$v+'px !important}';
                        }
                    } else if ($value.match('pl')){
                        let $v__ = $value;
                        $v__ = replace('pl', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // pl > 50
                            $style = '.'+$value_+'{padding-left:'+$v+'px !important}';
                        }
                    } else if ($value.match('pr')){
                        let $v__ = $value;
                        $v__ = replace('pr', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // pr > 50
                            $style = '.'+$value_+'{padding-right:'+$v+'px !important}';
                        }
                    } else {
                        let $v__ = $value;
                        $v__ = replace('p', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // p > 50
                            $style = '.'+$value_+'{padding:'+$v+'px !important}';
                        }
                    }

                    // MEDIA
                        if ($style_media){
                            $style = '@media screen AND (max-width:'+(parseInt($style_media)-1)+'px){ '+$style+' }';
                        }
                    // MEDIA
                }
            // PADDING

            // MARGIN
                else if ($value.match("m1") || $value.match("m2") || $value.match("m3") || $value.match("m4") || $value.match("m5") || $value.match("m6") || $value.match("m7") || $value.match("m8") || $value.match("m9") || $value.match("m0") || $value.match("mt") || $value.match("mb") || $value.match("ml") || $value.match("mr") || $value.match("m--") || $value.match("mt--") || $value.match("mb--") || $value.match("ml--") || $value.match("mr--")){
                    // MEDIA
                        let $value_ = $value;

                        let $style_media = '';
                        if ($value.match('_')){
                            let $ex = $value.split("_");
                            $style_media = $ex[1];
                            $value = $ex[0];
                        }
                    // MEDIA

                    if ($value.match('mt--')){
                        let $v = $value;
                        $v = replace('mt--', '', $v);
                        $style = '.'+$value_+'{margin-top:-'+$v+'px !important}';
                    } else if ($value.match('mb--')){
                        let $v = $value;
                        $v = replace('mb--', '', $v);
                        $style = '.'+$value_+'{margin-bottom:-'+$v+'px !important}';
                    } else if ($value.match('ml--')){
                        let $v = $value;
                        $v = replace('ml--', '', $v);
                        $style = '.'+$value_+'{margin-left:-'+$v+'px !important}';
                    } else if ($value.match('mr--')){
                        let $v = $value;
                        $v = replace('mr--', '', $v);
                        $style = '.'+$value_+'{margin-right:-'+$v+'px !important}';
                    } else if ($value.match('m--')){
                        let $v = $value;
                        $v = replace('m--', '', $v);
                        $style = '.'+$value_+'{margin:-'+$v+'px !important}';

                    } else if ($value.match('mt')){
                        let $v__ = $value;
                        $v__ = replace('mt', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // mt > 50
                            $style = '.'+$value_+'{margin-top:'+$v+'px !important}';
                        }
                    } else if ($value.match('mb')){
                        let $v__ = $value;
                        $v__ = replace('mb', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // mb > 50
                            $style = '.'+$value_+'{margin-bottom:'+$v+'px !important}';
                        }
                    } else if ($value.match('ml')){
                        let $v__ = $value;
                        $v__ = replace('ml', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // ml > 50
                            $style = '.'+$value_+'{margin-left:'+$v+'px !important}';
                        }
                    } else if ($value.match('mr')){
                        let $v__ = $value;
                        $v__ = replace('mr', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // mr > 50
                            $style = '.'+$value_+'{margin-right:'+$v+'px !important}';
                        }
                    } else {
                        let $v__ = $value;
                        $v__ = replace('m', '', $v__);
                        let $v = parseFloat($v__);
                        if ($v > 50 || $style_media){ // m > 50
                            $style = '.'+$value_+'{margin:'+$v+'px !important}';
                        }
                    }

                    // MEDIA
                        if ($style_media){
                            $style = '@media screen AND (max-width:'+(parseInt($style_media)-1)+'px){ '+$style+' }';
                        }
                    // MEDIA
                }
            // MARGIN


            // BORDER RADUIS
                else if ($value.match("brt")){
                    let $ex = $value.split("brt");
                    if ($ex[1]){
                        $style = '.brt'+$ex[1]+'{border-radius: '+$ex[1]+'px '+$ex[1]+'px 0 0 !important;-moz-border-radius: '+$ex[1]+'px '+$ex[1]+'px 0 0 !important;-webkit-border-radius:'+$ex[1]+'px '+$ex[1]+'px 0 0 !important}';
                    }
                }
                else if ($value.match("brb")){
                    let $ex = $value.split("brb");
                    if ($ex[1]){
                        $style = '.brb'+$ex[1]+'{border-radius: 0 0 '+$ex[1]+'px '+$ex[1]+'px !important;-moz-border-radius: 0 0 '+$ex[1]+'px '+$ex[1]+'px !important;-webkit-border-radius:0 0 '+$ex[1]+'px '+$ex[1]+'px !important}';
                    }
                }
                else if ($value.match("brl")){
                    let $ex = $value.split("brl");
                    if ($ex[1]){
                        $style = '.brl'+$ex[1]+'{border-radius: '+$ex[1]+'px 0 0 '+$ex[1]+'px !important;-moz-border-radius: '+$ex[1]+'px 0 0 '+$ex[1]+'px !important;-webkit-border-radius:'+$ex[1]+'px 0 0 '+$ex[1]+'px !important}';
                    }
                }
                else if ($value.match("brr")){
                    let $ex = $value.split("brr");
                    if ($ex[1]){
                        $style = '.brr'+$ex[1]+'{border-radius: 0 '+$ex[1]+'px '+$ex[1]+'px 0 !important;-moz-border-radius: 0 '+$ex[1]+'px '+$ex[1]+'px 0 !important;-webkit-border-radius:0 '+$ex[1]+'px '+$ex[1]+'px 0 !important}';
                    }
                }
                else if ($value.match("br")){
                    let $ex = $value.split("br");
                    if ($ex[1]){
                        $style = '.br'+$ex[1]+'{border-radius: '+$ex[1]+'px !important;-moz-border-radius: '+$ex[1]+'px !important;-webkit-border-radius:'+$ex[1]+'px !important}';
                    }
                }
            // BORDER RADUIS


            // RESPONSIVO
                $responsivooo.map(function($val1, $key1){
                    if ($value.match($val1)){
                        let $ex = $value.split($val1);
                        if ($ex[1]){
                            $style = '@media screen AND (max-width: '+(parseInt($ex[1])-1)+'px){.'+$value+'{'+$responsivooo1[$key1]+' !important;}} ';
                        }
                        if ($value.match("dnn_") || $value.match("dib_") || $value.match("dii_")){
                            $style = '.'+$value+'{display: none !important;}'+$style;
                        }
                    }
                });	
            // RESPONSIVO


            if ($attr && $val){
                $style = $outros+'.'+$value+$outros1+'{'+$attr+':'+$val+'}';
            }
            if ($media){
                $style = '@media screen AND (max-width: '+(parseInt($media)-1)+'px){'+$style+'} ';
                // if ($media_extra){
                //     $style = $media_extra+$style;
                // }
            }

            return $style;
        }
    // CRIANDOOO







    // CLASSE PAI
        function classe_pai($classe: string): string
        {
            let $return: string = '';

            $return += $classe+'[class*="bg_"],';
            $return += $classe+'[class*="bd_"],';
            $return += $classe+'[class*="bdt_"],';
            $return += $classe+'[class*="bdb_"],';
            $return += $classe+'[class*="bdl_"],';
            $return += $classe+'[class*="bdr_"],';
            $return += $classe+'[class*="bdw"],';
            $return += $classe+'[class*="c_"],';

            $return += $classe+'[class*="min-w"],';
            $return += $classe+'[class*="max-w"],';
            $return += $classe+'[class*="min-h"],';
            $return += $classe+'[class*="max-h"],';

            // RESPONSIVO
                $responsivooo.map(function($val1: string, $key1: number){
                    $return += $classe+'[class*=" '+$val1+'"],';
                });
            // RESPONSIVO

            for (let $i=1; $i<=60; $i++){ // ~ (busca palavra completa) encontra ta em class="ta to" | * (like) encontra ta em class="tala tava"
                $return += $classe+'[class~="br'+$i+'"],';
                $return += $classe+'[class~="brt'+$i+'"],';
                $return += $classe+'[class~="brb'+$i+'"],';
                $return += $classe+'[class~="brl'+$i+'"],';
                $return += $classe+'[class~="brr'+$i+'"],';
                if ($i==10 || $i==15 || $i==20 || $i==25 || $i==30 || $i==35 || $i==40 || $i==45 || $i==50){
                    $i = $i+4;
                }
            }

            for (let $i=1; $i<10; $i++){
                $return += $classe+'[class*="w'+$i+'"],';
                $return += $classe+'[class*="h'+$i+'"],';
                $return += $classe+'[class*="lh'+$i+'"],';
                $return += $classe+'[class*="fz'+$i+'"],';
                $return += $classe+'[class*="p'+$i+'"],';
                $return += $classe+'[class*="pt'+$i+'"],';
                $return += $classe+'[class*="pb'+$i+'"],';
                $return += $classe+'[class*="pl'+$i+'"],';
                $return += $classe+'[class*="pr'+$i+'"],';
                $return += $classe+'[class*="m'+$i+'"],';
                $return += $classe+'[class*="mt'+$i+'"],';
                $return += $classe+'[class*="mb'+$i+'"],';
                $return += $classe+'[class*="ml'+$i+'"],';
                $return += $classe+'[class*="mr'+$i+'"],';
                $return += $classe+'[class*="m--'+$i+'"],';
                $return += $classe+'[class*="mt--'+$i+'"],';
                $return += $classe+'[class*="mb--'+$i+'"],';
                $return += $classe+'[class*="ml--'+$i+'"],';
                $return += $classe+'[class*="mr--'+$i+'"],';
            }

            $return += $classe+'[class*="calc"]';

            //console.log($return);

            return $return;
        }
    // CLASSE PAI







    // VERIFICANDO CLASSES
        function verificando_classes($val: string): number
        {
            let $return: number = 0;

            if ($val.match('bg_'))			$return = 1;
            else if ($val.match('bd_'))		$return = 1;
            else if ($val.match('bdt_'))		$return = 1;
            else if ($val.match('bdb_'))		$return = 1;
            else if ($val.match('bdl_'))		$return = 1;
            else if ($val.match('bdr_'))		$return = 1;
            else if ($val.match('bdw'))		$return = 1;
            else if ($val.match('c_'))		$return = 1;

            else if ($val.match('min-w'))	$return = 1;
            else if ($val.match('max-w'))	$return = 1;
            else if ($val.match('min-h'))	$return = 1;
            else if ($val.match('max-h'))	$return = 1;

            else if ($val.match('calc'))		$return = 1;

            else if ($val.match('w1')  || $val.match('w2')  || $val.match('w3')  || $val.match('w4')  || $val.match('w5')  || $val.match('w6')  || $val.match('w7')  || $val.match('w8')  || $val.match('w9')  || $val.match('w0'))		$return = 1;
            else if ($val.match('h1')  || $val.match('h2')  || $val.match('h3')  || $val.match('h4')  || $val.match('h5')  || $val.match('h6')  || $val.match('h7')  || $val.match('h8')  || $val.match('h9')  || $val.match('h0'))		$return = 1;

            else if ($val.match('fz1')  || $val.match('fz2')  || $val.match('fz3')  || $val.match('fz4')  || $val.match('fz5')  || $val.match('fz6')  || $val.match('fz7')  || $val.match('fz8')  || $val.match('fz9')  || $val.match('fz0'))		$return = 1;

            else if ($val.match('p1')   || $val.match('p2')   || $val.match('p3')   || $val.match('p4')   || $val.match('p5')   || $val.match('p6')   || $val.match('p7')   || $val.match('p8')   || $val.match('p9')  || $val.match('p0'))			$return = 1;
            else if ($val.match('pt1')  || $val.match('pt2')  || $val.match('pt3')  || $val.match('pt4')  || $val.match('pt5')  || $val.match('pt6')  || $val.match('pt7')  || $val.match('pt8')  || $val.match('pt9')  || $val.match('pt0'))		$return = 1;
            else if ($val.match('pb1')  || $val.match('pb2')  || $val.match('pb3')  || $val.match('pb4')  || $val.match('pb5')  || $val.match('pb6')  || $val.match('pb7')  || $val.match('pb8')  || $val.match('pb9')  || $val.match('pb0'))		$return = 1;
            else if ($val.match('pl1')  || $val.match('pl2')  || $val.match('pl3')  || $val.match('pl4')  || $val.match('pl5')  || $val.match('pl6')  || $val.match('pl7')  || $val.match('pl8')  || $val.match('pl9')  || $val.match('pl0'))		$return = 1;
            else if ($val.match('pr1')  || $val.match('pr2')  || $val.match('pr3')  || $val.match('pr4')  || $val.match('pr5')  || $val.match('pr6')  || $val.match('pr7')  || $val.match('pr8')  || $val.match('pr9')  || $val.match('pr0'))		$return = 1;

            else if ($val.match('m1')   || $val.match('m2')   || $val.match('m3')   || $val.match('m4')   || $val.match('m5')   || $val.match('m6')   || $val.match('m7')   || $val.match('m8')   || $val.match('m9')  || $val.match('m0'))			$return = 1;
            else if ($val.match('mt1')  || $val.match('mt2')  || $val.match('mt3')  || $val.match('mt4')  || $val.match('mt5')  || $val.match('mt6')  || $val.match('mt7')  || $val.match('mt8')  || $val.match('mt9')  || $val.match('mt0'))		$return = 1;
            else if ($val.match('mb1')  || $val.match('mb2')  || $val.match('mb3')  || $val.match('mb4')  || $val.match('mb5')  || $val.match('mb6')  || $val.match('mb7')  || $val.match('mb8')  || $val.match('mb9')  || $val.match('mb0'))		$return = 1;
            else if ($val.match('ml1')  || $val.match('ml2')  || $val.match('ml3')  || $val.match('ml4')  || $val.match('ml5')  || $val.match('ml6')  || $val.match('ml7')  || $val.match('ml8')  || $val.match('ml9')  || $val.match('ml0'))		$return = 1;
            else if ($val.match('mr1')  || $val.match('mr2')  || $val.match('mr3')  || $val.match('mr4')  || $val.match('mr5')  || $val.match('mr6')  || $val.match('mr7')  || $val.match('mr8')  || $val.match('mr9')  || $val.match('mr0'))		$return = 1;

            else if ($val.match('m--1')   || $val.match('m--2')   || $val.match('m--3')   || $val.match('m--4')   || $val.match('m--5')   || $val.match('m--6')   || $val.match('m--7')   || $val.match('m--8')   || $val.match('m--9')   || $val.match('m--0'))			$return = 1;
            else if ($val.match('mt--1')  || $val.match('mt--2')  || $val.match('mt--3')  || $val.match('mt--4')  || $val.match('mt--5')  || $val.match('mt--6')  || $val.match('mt--7')  || $val.match('mt--8')  || $val.match('mt--9')  || $val.match('mt--0'))		$return = 1;
            else if ($val.match('mb--1')  || $val.match('mb--2')  || $val.match('mb--3')  || $val.match('mb--4')  || $val.match('mb--5')  || $val.match('mb--6')  || $val.match('mb--7')  || $val.match('mb--8')  || $val.match('mb--9')  || $val.match('mb--0'))		$return = 1;
            else if ($val.match('ml--1')  || $val.match('ml--2')  || $val.match('ml--3')  || $val.match('ml--4')  || $val.match('ml--5')  || $val.match('ml--6')  || $val.match('ml--7')  || $val.match('ml--8')  || $val.match('ml--9')  || $val.match('ml--0'))		$return = 1;
            else if ($val.match('mr--1')  || $val.match('mr--2')  || $val.match('mr--3')  || $val.match('mr--4')  || $val.match('mr--5')  || $val.match('mr--6')  || $val.match('mr--7')  || $val.match('mr--8')  || $val.match('mr--9')  || $val.match('mr--0'))		$return = 1;

            else if ($val.match('br1')  || $val.match('br2')  || $val.match('br3')  || $val.match('br4')  || $val.match('br5')  || $val.match('br6')  || $val.match('br7')  || $val.match('br8')  || $val.match('br9'))		$return = 1;
            else if ($val.match('brt1') || $val.match('brt2') || $val.match('brt3') || $val.match('brt4') || $val.match('brt5') || $val.match('brt6') || $val.match('brt7') || $val.match('brt8') || $val.match('brt9'))		$return = 1;
            else if ($val.match('brb1') || $val.match('brb2') || $val.match('brb3') || $val.match('brb4') || $val.match('brb5') || $val.match('brb6') || $val.match('brb7') || $val.match('brb8') || $val.match('brb9'))		$return = 1;
            else if ($val.match('brl1') || $val.match('brl2') || $val.match('brl3') || $val.match('brl4') || $val.match('brl5') || $val.match('brl6') || $val.match('brl7') || $val.match('brl8') || $val.match('brl9'))		$return = 1;
            else if ($val.match('brr1') || $val.match('brr2') || $val.match('brr3') || $val.match('brr4') || $val.match('brr5') || $val.match('brr6') || $val.match('brr7') || $val.match('brr8') || $val.match('brr9'))		$return = 1;

            return $return;					
        }
    // VERIFICANDO CLASSES





    // HEX
        export function HEX(): void
        {
            let $head = document.querySelector('head')
            if ($head){
                let $style = `<style>:root { --HEX_1: #${$_GLOBAL.HEX_1}; --HEX_2: #${$_GLOBAL.HEX_2}; --HEX_3: #${$_GLOBAL.HEX_3}; --HEX_4: #${$_GLOBAL.HEX_4}; --HEX_5: #${$_GLOBAL.HEX_5}; --HEX_6: #${$_GLOBAL.HEX_6}; --HEX_7: #${$_GLOBAL.HEX_7}; --HEX_8: #${$_GLOBAL.HEX_8}; --HEX_9: #${$_GLOBAL.HEX_9}; --HEX_10: #${$_GLOBAL.HEX_10}; }</style> `;
                $head.insertAdjacentHTML('beforeend', $style);                
            }
        }
    // HEX