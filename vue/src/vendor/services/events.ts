import api from '@/vendor/services/api';
import { APP, DIR, DIR_API, DIR_P, LOCALHOST } from '@/vendor/services/localhost';
import { rootAuth } from '@/vendor/storages/auth';
import { NEW__url_admin_dashboard, NEW__request_obj, NEW__open__queryParams, NEW__open__pg, admin__menu_side } from '@/services/NEW__events';





    // CART
        // SAVE
            export function cart_save(value: any, qty: number = 1): void
            {
                let form: any = {
                    id: value.id,
                    qty: qty,
                    variants: 0,
                    _method: 'PUT',
                };
                api(`/dashboard/cart/save`, form, (json: any) => {
                    if($_GET[1] != `cart`){
                        $_GLOBAL.SHOW.CART_SIDE = true;
                    }
                });
            }
        // SAVE





        // QTY
            export function cart_qty(value: any, qty: number = 1): void
            {
                let form: any = {
                    id: value.id,
                    qty: qty==null ? value.qty : qty,
                    variants: 0,
                    _method: 'PUT',
                };
                api(`/dashboard/cart/qty`, form, (json: any) => {
                    if($_GET[1] != `cart`){
                        $_GLOBAL.SHOW.CART_SIDE = true;
                    }
                    $_GLOBAL.FORM.v.card_cvv = ``;
                    $_GLOBAL.FORM.v.card_installments = 1;
                });
            }
        // QTY





        // DELETE
            export function cart_delete(value: any): void
            {
                let form: any = {
                    id: value.id,
                    delete: value.id,
                    variants: 0,
                    _method: 'PUT',
                };
                api(`/dashboard/cart/delete`, form, (json: any) => {
                    if($_GET[1] != `cart`){
                        $_GLOBAL.SHOW.CART_SIDE = true;
                    }
                    $_GLOBAL.FORM.v.card_cvv = ``;
                    $_GLOBAL.FORM.v.card_installments = 1;
                });
            }
        // DELETE





        // TYPE
            export function cart_type(type: string ): number
            {
                // LOGIN
                    if(type == `login`){
                        if($_GLOBAL.OBJ.CART?.position==`login`){
                            return 1;

                        } else if(split(`address`, $_GLOBAL.OBJ.CART?.position) || $_GLOBAL.OBJ.CART?.position==`shipping` || $_GLOBAL.OBJ.CART?.position==`pay`){
                            return 2;
                        }
                    }
                // LOGIN

                // ADDRESS
                    if(type == `address`){
                        if(split(`address`, $_GLOBAL.OBJ.CART?.position) || $_GLOBAL.OBJ.CART?.position==`shipping`){
                            return 1;

                        } else if($_GLOBAL.OBJ.CART?.position==`pay`){
                            return 2;
                        }
                    }
                // ADDRESS

                // PAY
                    if(type == `pay`){
                        if($_GLOBAL.OBJ.CART?.position==`pay`){
                            return 1;
                        }
                    }
                // PAY

                return 0;
            }
        // TYPE
    // CART




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // ARRAY / OBJECT
        // CREATE_EDIT_ACTIVE_OPTIONS
            interface ActiveOption {
                id: number;
                name: string;
            }
            export function create_edit_active_options(): ActiveOption[]
            {
                return [
                    {
                        id: 1,
                        name: `Ativo`,
                    },
                    {
                        id: 0,
                        name: `Desativado`,
                    },
                ]
            }
        // CREATE_EDIT_ACTIVE_OPTIONS


        // MONTH
            export function month(month: number | string): string
            {
                let months = [`Janeiro`, `Fevereiro`, `Março`, `Abril`, `Maio`, `Junho`, `Julho`, `Agosto`, `Setembro`, `Outubro`, `Novembro`, `Dezembro`];
                let index = typeof month === `string` ? parseInt(month, 10) : month;
                return months[index - 1] || ``;
            }
            export function month_ab(month: number | string): string
            {
                let months = [`Jan`, `Fev`, `Mar`, `Abr`, `Mai`, `Jun`, `Jul`, `Ago`, `Set`, `Out`, `Nov`, `Dez`];
                let index = typeof month === `string` ? parseInt(month, 10) : month;
                return months[index - 1] || ``;
            }

            interface MonthOption {
                id: number;
                name: string;
                selected: boolean;
            }
            export function month_select(month: number = 0): MonthOption[]
            {
                let result = [
                            {'id': 1, 'name': 'Janeiro', 'selected': (month==1)}, 
                            {'id': 2, 'name': 'Fevereiro', 'selected': (month==2)}, 
                            {'id': 3, 'name': 'Março', 'selected': (month==3)}, 
                            {'id': 4, 'name': 'Abril', 'selected': (month==4)}, 
                            {'id': 5, 'name': 'Maio', 'selected': (month==5)}, 
                            {'id': 6, 'name': 'Junho', 'selected': (month==6)}, 
                            {'id': 7, 'name': 'Julho', 'selected': (month==7)}, 
                            {'id': 8, 'name': 'Agosto', 'selected': (month==8)}, 
                            {'id': 9, 'name': 'Setembro', 'selected': (month==9)}, 
                            {'id': 10, 'name': 'Outubro', 'selected': (month==10)}, 
                            {'id': 11, 'name': 'Novembro', 'selected': (month==11)}, 
                            {'id': 12, 'name': 'Dezembro', 'selected': (month==12)}
                        ];
                return result;
            }
        // MONTH


        // UF
            interface UFOption {
                id: string;
                name: string;
                selected: boolean;
            }
            export function uf(uf: string = ''): UFOption[]
            {
                let result = [
                            {'id': 'AC', 'name': 'Acre', 'selected': (uf=='AC')}, 
                            {'id': 'AL', 'name': 'Alagoas', 'selected': (uf=='AL')}, 
                            {'id': 'AM', 'name': 'Amazonas', 'selected': (uf=='AM')}, 
                            {'id': 'AP', 'name': 'Amapá', 'selected': (uf=='AP')}, 
                            {'id': 'BA', 'name': 'Bahia', 'selected': (uf=='BA')}, 
                            {'id': 'CE', 'name': 'Ceará', 'selected': (uf=='CE')}, 
                            {'id': 'DF', 'name': 'Distrito Federal', 'selected': (uf=='DF')}, 
                            {'id': 'ES', 'name': 'Espírito Santo', 'selected': (uf=='ES')}, 
                            {'id': 'GO', 'name': 'Goiás', 'selected': (uf=='GO')}, 
                            {'id': 'MA', 'name': 'Maranhão', 'selected': (uf=='MA')}, 
                            {'id': 'MG', 'name': 'Minas Gerais', 'selected': (uf=='MG')}, 
                            {'id': 'MS', 'name': 'Mato Grosso do Sul', 'selected': (uf=='MS')}, 
                            {'id': 'MT', 'name': 'Mato Grosso', 'selected': (uf=='MT')}, 
                            {'id': 'PA', 'name': 'Pará', 'selected': (uf=='PA')}, 
                            {'id': 'PB', 'name': 'Paraíba', 'selected': (uf=='PB')}, 
                            {'id': 'PE', 'name': 'Pernambuco', 'selected': (uf=='PE')}, 
                            {'id': 'PI', 'name': 'Piauí', 'selected': (uf=='PI')}, 
                            {'id': 'PR', 'name': 'Paraná', 'selected': (uf=='PR')}, 
                            {'id': 'RJ', 'name': 'Rio de Janeiro', 'selected': (uf=='RJ')}, 
                            {'id': 'RN', 'name': 'Rio Grande do Norte', 'selected': (uf=='RN')}, 
                            {'id': 'RO', 'name': 'Rondônia', 'selected': (uf=='RO')}, 
                            {'id': 'RR', 'name': 'Roraima', 'selected': (uf=='RR')}, 
                            {'id': 'RS', 'name': 'Rio Grande do Sul', 'selected': (uf=='RS')}, 
                            {'id': 'SC', 'name': 'Santa Catarina', 'selected': (uf=='SC')}, 
                            {'id': 'SE', 'name': 'Sergipe', 'selected': (uf=='SE')}, 
                            {'id': 'SP', 'name': 'São Paulo', 'selected': (uf=='SP')}, 
                            {'id': 'TO', 'name': 'Tocantins', 'selected': (uf=='TO')}, 
                        ];
                return result;
            }
        // UF


        // YEARS__
            interface YearOption {
                id: number;
                name: number;
            }
            export function years__(extra: string): YearOption[]
            {
                let $ex = extra.split('->');
                let currentYear = new Date().getFullYear();
                let year_final = parseInt($ex[2] != undefined ? $ex[2] : '1950');
                let result: Array<{id: number, name: number}> = [];
                for (let year = currentYear; year >= year_final; year--) {
                    result.push({'id': year, 'name': year});
                }
                return result;
            }
        // YEARS__
    // ARRAY / OBJECT




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // HTML
        // IMG
            interface ImageValue {
                [key: string]: unknown;
                name?: string;
                table__?: string;
                type?: string;
                place?: string;
            }
            export function img(value: ImageValue, width: number = 0, height: string | number = ``, $class: string | number = ``, image: string = `image`, type: string = ``): string
            {
                let src = value?.[image];
                let $img_default = `${DIR_P()}/img/default/${$class === 1 ? `file_error_1` : `file_error`}.jpg`;

                // THUMBS_SIZE_DEFAULT
                    let $reponse = img_thumbs_size_default(value, image, width, height);
                    width = $reponse[0];
                    height = $reponse[1];
                // THUMBS_SIZE_DEFAULT


                // MOBILE
                    if (MOBILE() && type == `mobile`){
                        // src = value[image_mobile];
                    }
                // MOBILE


                // TYPE
                    else if (type){

                    }
                // TYPE


                let result = ``;
                if (src){
                    // BACK
                        if ($class === 1){
                            result = src as string;
                        }
                    // BACK

                    // IMG
                        else {
                            result = `<img src="${src}" class="${$class}" style="max-width: 100%; max-height: ${height}px;" ${value?.name ? `title="${strip_tags__(value.name)}" alt="${strip_tags__(value.name)}" name="${strip_tags__(value.name)}"` : ``} onerror="this.onerror=null; this.src='${$img_default}'" />`;
                        }
                    // IMG

                } else if ($class !== 2){
                    result = img_default(width, typeof height === 'string' ? parseInt(height, 10) : height, $class, $img_default);
                }

                return html_treatment(result);
            }


            export function img_default(width: number, height: number, $class: string | number = ``, $img_default: string = ``): string
            {
                let result = '';

                // BACK
                    if ($class === 1){
                        result = $img_default;
                    }
                // BACK

                // SVG
                    else {
                        result = `<div class="w${width} h${height} mb5 m-a flexx flex_c flex_ac c_666 bg_fff br5 ${$class}"><div class="w${width*40/100} h${width*40/100}"><svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M3 8.976C3 4.05476 4.05476 3 8.976 3H15.024C19.9452 3 21 4.05476 21 8.976V15.024C21 19.9452 19.9452 21 15.024 21H8.976C4.05476 21 3 19.9452 3 15.024V8.976Z" stroke="currentColor" stroke-width="2"></path> <path d="M17.0045 16.5022L12.7279 12.2256C9.24808 8.74578 7.75642 8.74578 4.27658 12.2256L3 13.5022" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path> <path d="M21 13.6702C18.9068 12.0667 17.4778 12.2919 15.198 14.3459" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path> <path d="M17 8C17 8.55228 16.5523 9 16 9C15.4477 9 15 8.55228 15 8C15 7.44772 15.4477 7 16 7C16.5523 7 17 7.44772 17 8Z" stroke="currentColor" stroke-width="2"></path> </g></svg></div></div>`;
                    }
                // SVG

                return html_treatment(result);
            }


            export function img_thumbs_size_default(value: ImageValue, image: string, width: number, height: string | number): [number, string | number]
            {
                if ($_GLOBAL.OBJ?.__GLOBAL__?.__THUMBS__ && height === ``) {
                    for (const [key_1, value_1] of Object.entries($_GLOBAL.OBJ.__GLOBAL__.__THUMBS__)) {
                        // TABLE
                        if (key_1 === value?.table__) {
                            const value_1_typed = value_1 as Record<string, any>;
                            if (!value_1_typed.width) {
                                for (const [key_2, value_2] of Object.entries(value_1 as Record<string, any>)) {
                                    // FIELD
                                    if (value_2?.width) {
                                        if (key_2 === image) {
                                            width = value_2.width;
                                            height = value_2.height;
                                        }
                                    } else {
                                        for (const [key_3, value_3] of Object.entries(value_2 as Record<string, any>)) {
                                            // TYPE
                                            if (value_3?.width) {
                                                if (value.type !== undefined && key_3 === value.type) {
                                                    width = value_3.width;
                                                    height = value_3.height;
                                                }
                                            } else {
                                                for (const [key_4, value_4] of Object.entries(value_3 as Record<string, any>)) {
                                                    // PLACE
                                                    if (value_4?.width) {
                                                        if (value.place !== undefined && key_4 === value.place) {
                                                            width = value_4.width;
                                                            height = value_4.height;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                return [width, height];
            }
        // IMG










        // VIDEO
            export function video(url: string, width: string | number, height: string | number, get: string = '?autoplay=true&amp;mute=false&amp;rel=0&amp;controls=1&amp;showinfo=0'): string
            { 
                if(!url) return ``;

                let returnValue = '';
                
                if (url.includes('youtube') || url.includes('youtu.be')) {
                    returnValue = video_youtube(url, width, height, get);
                } else if (url.includes('vimeo')) {
                    returnValue = video_vimeo(url, width, height);
                } else if (url.includes('/web/fotos/')) {
                    returnValue = video_html(url, width, height);
                } else if (url.includes('instagram')) {
                    returnValue = video_instagram(url, width, height);
                }
                
                return returnValue;
            }

            // VIDEO YOUTUBE
            export function video_youtube(url: string, width: string | number, height: string | number, get: string = '?'): string
            {
                let returnValue = '';
                const videoUrl = video_youtubeUrl(url);
                const urlParts = videoUrl[1] ? videoUrl[1].split('&') : '';
                
                if (urlParts && urlParts[0]) {
                    returnValue = `<iframe width="${width}" height="${height}" class="max-w100p" src="https://www.youtube.com/embed/${urlParts[0]}${get}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                }
                
                return returnValue;
            }

            export function video_youtubeUrl(url: string | { link: string }): string[] {
                const urlString = typeof url === 'object' ? url.link : url;
                let processedUrl = urlString.replace('youtu.be/', 'youtu.be/?v=');
                let result: string[] = [];
                
                if (processedUrl.includes('v=')) {
                    result = processedUrl.split('v=');
                } else if (processedUrl.includes('embed/')) {
                    result = processedUrl.split('embed/');
                } else if (processedUrl.includes('shorts/')) {
                    result = processedUrl.split('shorts/');
                } else {
                    result = processedUrl.split('v&#61;');
                }
                
                return result;
            }

            export function video_youtubeImg(url: string, width: string = '100%', height: number = 0, classe: string = 'db max-w100p m-a'): string
            {
                const videoUrl = video_youtubeUrl(url);
                let returnValue = '';
                
                if (videoUrl[1]) {
                    const imgParts = videoUrl[1].split('&');
                    const imgId = imgParts[0].split('#')[0];
                    
                    let tamanho = ` width="${width}" `;
                    if (height) {
                        tamanho += ` height="${height}" `;
                    }
                    
                    returnValue = `<div class="posr">
                        <span class="play1_youtube"></span>
                        <span class="play2_youtube"></span>
                        <img src="http://i.ytimg.com/vi/${imgId}/maxresdefault.jpg" ${tamanho} class="${classe}" />
                    </div>`;
                }
                
                return returnValue;
            }

            // VIMEO
            export function video_vimeo(url: string, width: string | number, height: string | number): string
            {
                const autoplay = 1;
                const controls = 1;
                const speed = 1;
                const loop = 0;
                
                let processedUrl = url;
                let ex = processedUrl.split('.com/');
                processedUrl = ex[1] || ex[0];
                
                ex = processedUrl.split('.com.br/');
                processedUrl = ex[1] || ex[0];
                
                const infos = `title=0&amp;byline=0&amp;portrait=0&amp;speed=${speed}&amp;autoplay=${autoplay}&amp;autopause=0&amp;controls=${controls}&amp;loop=${loop}&amp;transparent=0`;
                
                const urlParts = processedUrl.split('/');
                if (urlParts[1]) {
                    const hashParts = urlParts[1].split('?');
                    processedUrl = `${urlParts[0]}?h=${hashParts[0]}&${infos}`;
                } else {
                    processedUrl = `${processedUrl}?${infos}`;
                }
                
                return `<iframe src="https://player.vimeo.com/video/${processedUrl}" width="${width}" height="${height}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>`;
            }

            export function video_vimeo_img(url: string): string
            {
                let processedUrl = url;
                let ex = processedUrl.split('.com/');
                processedUrl = ex[1] || ex[0];
                
                ex = processedUrl.split('.com.br/');
                processedUrl = ex[1] || ex[0];
                
                return `https://vumbnail.com/${processedUrl}.jpg`;
            }

            // VIDEO HTML
            export function video_html(url: string, width: string | number, height: string | number): string
            {
                return `<video width="${width}" height="${height}" controls autoplay muted>
                    <source src="${url}" type="video/mp4">
                    <source src="${url}" type="video/mov">
                    <source src="${url}" type="video/avi">
                </video>`;
            }

            // VIDEO INSTAGRAM
            export function video_instagram(url: string, width: string | number, height: string | number): string
            {
                let processedUrl = `${url}/embed/`;
                processedUrl = processedUrl.replace('//', '/');
                
                return `<iframe align="middle" width="${width}" height="${height}" class="max-w100p" src="${processedUrl}" frameborder="0" allowfullscreen></iframe>`;
            }
        // VIDEO
    // HTML




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // DATE
        // DATE__
            export function date__(value: string | number | null, format: string = $_GLOBAL.OBJ?.date_format ? $_GLOBAL.OBJ?.date_format : 'd/m/Y'): string {
                if (value === null || value === '') {
                    return '';
                }

                // Se for timestamp (número)
                if (typeof value === 'number' || /^\d+$/.test(String(value))) {
                    const timestamp = parseInt(String(value));
                    const date = new Date(timestamp * 1000); // JS usa millisegundos
                    return date_format(date, format);
                }

                // Tentar diferentes formatos de entrada
                const formats = [
                    { regex: /^(\d{4})-(\d{2})-(\d{2})$/, parts: ['year', 'month', 'day'] }, // 2025-01-21
                    { regex: /^(\d{2})\/(\d{2})\/(\d{4})$/, parts: ['day', 'month', 'year'] }, // 21/01/2025
                    { regex: /^(\d{2})-(\d{2})-(\d{4})$/, parts: ['day', 'month', 'year'] }, // 21-01-2025
                    { regex: /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/, parts: ['year', 'month', 'day', 'hour', 'minute', 'second'] }, // 2025-01-21 15:23:59
                    { regex: /^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})$/, parts: ['day', 'month', 'year', 'hour', 'minute', 'second'] }, // 21/01/2025 15:23:59
                ];

                for (const fmt of formats) {
                    const match = String(value).match(fmt.regex);
                    if (match) {
                        const dateParts: any = {};
                        fmt.parts.forEach((part, index) => {
                            dateParts[part] = parseInt(match[index + 1]);
                        });

                        const date = new Date(
                            dateParts.year || new Date().getFullYear(),
                            (dateParts.month || 1) - 1, // JS months are 0-indexed
                            dateParts.day || 1,
                            dateParts.hour || 0,
                            dateParts.minute || 0,
                            dateParts.second || 0
                        );

                        if (!isNaN(date.getTime())) {
                            return date_format(date, format);
                        }
                    }
                }

                return '';
            }

            export function date_format(date: Date, format: string): string {
                const pad = (num: number): string => String(num).padStart(2, '0');

                const replacements: { [key: string]: string } = {
                    'd': pad(date.getDate()),
                    'j': String(date.getDate()),
                    'm': pad(date.getMonth() + 1),
                    'n': String(date.getMonth() + 1),
                    'Y': String(date.getFullYear()),
                    'y': String(date.getFullYear()).slice(-2),
                    'H': pad(date.getHours()),
                    'G': String(date.getHours()),
                    'i': pad(date.getMinutes()),
                    's': pad(date.getSeconds()),
                };

                return format.replace(/[djmnYyHGis]/g, (match) => replacements[match] || match);
            }
        // DATE__


        // DATE ADD
            export function date_add(date: string | number | null, number: number, unit: string = 'd', format: string = $_GLOBAL.OBJ?.date_format ? $_GLOBAL.OBJ?.date_format : 'd/m/Y'): string | null {
                if (date === null || date === '') {
                    return null;
                }

                let dt: Date;

                try {
                    // Criar Date a partir da entrada
                    if (typeof date === 'number' || /^\d+$/.test(String(date))) {
                        const timestamp = parseInt(String(date));
                        dt = new Date(timestamp < 10000000000 ? timestamp * 1000 : timestamp);
                    } else {
                        // Parse direto da string
                        dt = new Date(String(date).replace(' ', 'T'));
                    }

                    if (isNaN(dt.getTime())) {
                        return null;
                    }
                } catch (e) {
                    return null;
                }

                // Mapear e aplicar modificação
                const operations: { [key: string]: () => void } = {
                    'y': () => dt.setFullYear(dt.getFullYear() + number),
                    'm': () => dt.setMonth(dt.getMonth() + number),
                    'w': () => dt.setDate(dt.getDate() + (number * 7)),
                    'd': () => dt.setDate(dt.getDate() + number),
                    'h': () => dt.setHours(dt.getHours() + number),
                    'i': () => dt.setMinutes(dt.getMinutes() + number),
                    's': () => dt.setSeconds(dt.getSeconds() + number),
                };

                const operation = operations[unit.toLowerCase()];
                if (!operation) {
                    return null; // unidade desconhecida
                }

                operation();

                // Formatar usando a função formatDate já definida
                const pad = (num: number): string => String(num).padStart(2, '0');

                const replacements: { [key: string]: string } = {
                    'd': pad(dt.getDate()),
                    'm': pad(dt.getMonth() + 1),
                    'Y': String(dt.getFullYear()),
                    'y': String(dt.getFullYear()).slice(-2),
                    'H': pad(dt.getHours()),
                    'i': pad(dt.getMinutes()),
                    's': pad(dt.getSeconds()),
                };

                return format.replace(/[dmYyHis]/g, (match) => replacements[match] || match);
            }
        // DATE ADD


        // DATE SUM
            export function date_sum(date1: string | number | null, date2: string | number | null, units: string = 's'): object | null  // 'y w d m s'
            {
                if (date1 === null || date2 === null || date1 === '' || date2 === '') {
                    return null;
                }
          
                let dt1: Date;
                let dt2: Date;
          
                try {
                    // Converter date1 para Date
                    if (typeof date1 === 'number' || /^\d+$/.test(String(date1))) {
                        const timestamp1 = parseInt(String(date1));
                        dt1 = new Date(timestamp1 < 10000000000 ? timestamp1 * 1000 : timestamp1);
                    } else {
                        dt1 = new Date(String(date1).replace(' ', 'T'));
                    }
          
                    // Converter date2 para Date
                    if (typeof date2 === 'number' || /^\d+$/.test(String(date2))) {
                        const timestamp2 = parseInt(String(date2));
                        dt2 = new Date(timestamp2 < 10000000000 ? timestamp2 * 1000 : timestamp2);
                    } else {
                        dt2 = new Date(String(date2).replace(' ', 'T'));
                    }
          
                    if (isNaN(dt1.getTime()) || isNaN(dt2.getTime())) {
                        return null;
                    }
                } catch (e) {
                    return null;
                }
          
                // Determinar o sinal (+ se date2 > date1, - caso contrário)
                const sign = dt2.getTime() > dt1.getTime() ? '+' : '-';
                const diffMilliseconds = Math.abs(dt1.getTime() - dt2.getTime());
                const diffSeconds = Math.floor(diffMilliseconds / 1000);
          
                // Segundos por unidade
                const secondsPer: { [key: string]: number } = {
                    'y': 365 * 86400,  // ano (aproximado)
                    'w': 7 * 86400,    // semana
                    'd': 86400,        // dia
                    'h': 3600,         // hora
                    'i': 60,           // minuto
                    's': 1,            // segundo
                };
          
                // Processar unidades solicitadas
                const requestedUnits = units.toLowerCase().split(/\s+/);
                const result: { [key: string]: any } = { sign };
                let remainingSeconds = diffSeconds;
          
                // Calcular cada unidade na ordem especificada
                for (const unit of requestedUnits) {
                    if (secondsPer[unit]) {
                        const value = Math.floor(remainingSeconds / secondsPer[unit]);
                        result[unit] = value;
                        remainingSeconds = remainingSeconds % secondsPer[unit];
                    }
                }
          
                return result;
            }
        // DATE SUM


        // DATE
            export function date(format: string, data: string = '', $day: number = 0, $month: number = 0, $year: number = 0, $hour: number = 0, $min: number = 0, $seg: number = 0): string
            {               
                // CRIA A DATA CORRETAMENTE, CONSIDERANDO DIFERENTES FORMATOS
                    data = data ? data : today();

                    data = data.replace(/-/g, '/'); // SUBSTITUI '-' POR '/' PARA EVITAR PROBLEMAS NO IOS

                    let date = new Date(data);
                    if (isNaN(date.getTime())) {
                        // Tenta formatar manualmente caso a data seja inválida
                        let parts = data.split('/');
                        if (parts.length === 3) {
                            date = new Date(parseInt(parts[2]), parseInt(parts[1]) - 1, parseInt(parts[0]));
                        } else {
                            console.error("Formato de data inválido:", data);
                            return "Data inválida";
                        }
                    }
                // CRIA A DATA CORRETAMENTE, CONSIDERANDO DIFERENTES FORMATOS

                date.setFullYear(date.getFullYear() + $year);
                date.setMonth(date.getMonth() + $month);
                date.setDate(date.getDate() + $day);
                date.setHours(date.getHours() + $hour);
                date.setMinutes(date.getMinutes() + $min);
                date.setSeconds(date.getSeconds() + $seg);

                const year = date.getFullYear();
                const month = ('0' + (date.getMonth() + 1)).slice(-2);
                const day = ('0' + date.getDate()).slice(-2);
                const hours = ('0' + date.getHours()).slice(-2);
                const minutes = ('0' + date.getMinutes()).slice(-2);
                const seconds = ('0' + date.getSeconds()).slice(-2);

                return format
                    .replace('Y', year.toString())
                    .replace('m', month)
                    .replace('d', day)
                    .replace('H', hours)
                    .replace('i', minutes)
                    .replace('s', seconds);
            }
        // DATE


        // DATA_SPLIT
            export function date_split($data: string, $tipo = '-'): string[]
            {
                let result: string[] = [];
                if (split($tipo, $data)){
                    $data = replace(' ', $tipo, $data);
                    $data = replace(':', $tipo, $data);
                    $data = replace('T', $tipo, $data);
                    $data = replace('BRT', $tipo, $data);
                    $data = replace('.', $tipo, $data);
                    $data = replace('000000', $tipo, $data);
                    $data = replace('Z', $tipo, $data);

                    const splitData = $data.split($tipo);
                    if (splitData[2] != null) {
                        splitData[3] = splitData[3] != null ? splitData[3] : '0';
                        splitData[4] = splitData[4] != null ? splitData[4] : '0';
                        splitData[5] = splitData[5] != null ? splitData[5] : '0';
                        result = splitData;
                    }
                }
                return  result;
            }
        // DATA_SPLIT


        // TODAY / NOW
            export function today(format: string = 'Y-m-d'): string
            {
                const d = new Date();
                const map: Record<string, string> = {
                    'Y': String(d.getFullYear()),
                    'm': ('0' + (d.getMonth() + 1)).slice(-2),
                    'd': ('0' + d.getDate()).slice(-2),
                    'H': ('0' + d.getHours()).slice(-2),
                    'i': ('0' + d.getMinutes()).slice(-2),
                    's': ('0' + d.getSeconds()).slice(-2),
                };
                return format.replace(/Y|m|d|H|i|s/g, token => map[token]);
            }
            export function now(format: string = 'Y-m-d'): string
            {
                return today(format);
            }
        // TODAY / NOW

        // BIRTH
            export function birth(): string
            {
                const TODAY = new Date();
                const TODAY_15 = new Date(TODAY.getFullYear(), TODAY.getMonth() - (18 * 12) + 1, TODAY.getDate());
                return `${TODAY_15.getFullYear()}-${('00' + (TODAY_15.getMonth() + 1)).slice(-2)}-${('00' + TODAY_15.getDate()).slice(-2)}`;
            }
        // BIRTH

        // DIA DA SEMANA
            export function dia_semana(data: string, tipo = '-'): string
            {
                const dataParts = data.split(tipo);
                const dia = parseInt(dataParts[2]);
                const mes = parseInt(dataParts[1]);
                const ano = parseInt(dataParts[0]);
                const diasemana = new Date(ano, mes - 1, dia).getDay();

                let returnDia: string;
                switch (diasemana) {
                    case 0: returnDia = "Domingo"; break;
                    case 1: returnDia = "Segunda"; break;
                    case 2: returnDia = "Terça"; break;
                    case 3: returnDia = "Quarta"; break;
                    case 4: returnDia = "Quinta"; break;
                    case 5: returnDia = "Sexta"; break;
                    case 6: returnDia = "Sábado"; break;
                    default: returnDia = ""; break;
                }
                return returnDia;
            }
        // DIA DA SEMANA

        // MÊS ABREVIAÇÃO
            export function mes(mes: number, ab = 0): string
            {
                let returnMes: string;
                switch(mes) {
                    case 1: returnMes = ab ? 'Jan'  : 'Janeiro'; break;
                    case 2: returnMes = ab ? 'Fev'  : 'Fevereiro'; break;
                    case 3: returnMes = ab ? 'Mar'  : 'Março'; break;
                    case 4: returnMes = ab ? 'Abr'  : 'Abril'; break;
                    case 5: returnMes = ab ? 'Mai'  : 'Maio'; break;
                    case 6: returnMes = ab ? 'Jun'  : 'Junho'; break;
                    case 7: returnMes = ab ? 'Jul'  : 'Julho'; break;
                    case 8: returnMes = ab ? 'Ago'  : 'Agosto'; break;
                    case 9: returnMes = ab ? 'Set'  : 'Setembro'; break;
                    case 10: returnMes = ab ? 'Out' : 'Outubro'; break;
                    case 11: returnMes = ab ? 'Nov' : 'Novembro'; break;
                    case 12: returnMes = ab ? 'Dez' : 'Dezembro'; break;
                    default: returnMes = ""; break;
                }
                return returnMes;
            }
        // MÊS ABREVIAÇÃO
    // DATE




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // NUMBERS
        // PRICE
            export function price(value: number, espaco = 0): string
            {
                let result = ``;
                if ($_GLOBAL.OBJ?.info?.currency == '$'){
                    const options: Intl.NumberFormatOptions = {
                        style: 'currency',
                        currency: 'USD'
                    }
                    result = new Intl.NumberFormat('en-us', options).format(value)
                    result = result.replace(`$`, `$ `);

                } else {
                    const options: Intl.NumberFormatOptions = {
                        style: 'currency',
                        currency: 'BRL'
                    }
                    result = new Intl.NumberFormat('pt-br', options).format(value)
                }

                return result;
            }
            export function price_1(value: number): string
            {
                let result = `${price(value, 0)}`;
                result = replace($_GLOBAL.OBJ?.info?.currency ? $_GLOBAL.OBJ?.info?.currency : `R$`, ``, result);
                result = replace($_GLOBAL.OBJ?.info?.currency_decimal ? $_GLOBAL.OBJ?.info?.currency_decimal : `,00`, ``, result);
                
                return result;
            }
            export function price_2(value: number): string
            {
                value = parseInt(value.toString());
                let result = `${price(value, 0)}`;
                result = replace($_GLOBAL.OBJ?.info?.currency ? $_GLOBAL.OBJ?.info?.currency : `R$`, ``, result);
                result = replace($_GLOBAL.OBJ?.info?.currency_decimal ? $_GLOBAL.OBJ?.info?.currency_decimal : `,00`, ``, result);

                if ((result.match(/\./g) || []).length == 1){
                    let $ex = result.split(`.`);
                    result = `${$ex[0]}K`;

                } else if ((result.match(/\./g) || []).length == 2){
                    let $ex = result.split(`.`);
                    result = `${$ex[0]}.${$ex[1]}M`;

                } else if ((result.match(/\./g) || []).length == 3){
                    let $ex = result.split(`.`);
                    result = `${$ex[0]}.${$ex[1]}B`;

                }

                return result;
            }
        // PRICE

    
        // PRICE_
            export function price_(value: string): number
            {
                value = replace(` `, ``, value);
                if ($_GLOBAL.OBJ?.info?.currency){
                    value = replace($_GLOBAL.OBJ?.info?.currency, ``, value);

                } else {
                    value = replace(`R$`, ``, value);
                }
                value = replace(`,`, ``, value);
                value = replace(`.`, ``, value);

                const result = parseInt(value);
                return result/100;
            }
        // PRICE_


        // NUMBER
            export function number(str: unknown): string
            {
                return String(str).replace(/\D+/g, '');
            }
        // NUMBER


        // DECIMAL
            export function decimal(number: number, decimal: number = 2): string
            {
                const num = Number(number);
                if (isNaN(num)) return "0";
                return num
                .toFixed(decimal)
                .replace(/\.?0+$/, '');
            }
        // DECIMAL


        // PERCENT
            export function perc(value: number | string): string
            {
                const num = Number(value);
                if (isNaN(num)) return "0";
                return parseFloat(num.toFixed(2)).toString();
            }
            export function percent(value: number | string): string
            {
                const num = Number(value);
                if (isNaN(num)) return "0";
                return parseFloat(num.toFixed(2)).toString();
            }
        // PERCENT


        // ZERO_LEFT
            export function zero_left(numero: number, tamanho: number = 2): string
            {
                return String(numero).padStart(tamanho, '0');
            }
        // ZERO_LEFT


        // NUMBER_POINT
            export function number_point(value: number | string): string
            {
                return value.toLocaleString('pt-BR');
            }
        // NUMBER_POINT


        // RAND
            export function rand(min=100000, max=999999)
            {
                let result = Math.floor(Math.random() * (max - min + 1) + min);
                return result;
            }
            export function rand__(): number
            {
                return Math.floor(Math.random() * 100000) + 1;
            }
            export function rand_str()
            {
                let result = (Math.random() + 1).toString(36).substring(7);
                return result;
            }
        // RAND


        // PHONE
            export function phone(numero: string): string
            {
                let retorno = numero;
                if (numero.length > 14) {
                    numero = numero.replace(/-/g, '');
                    const ini = numero.substring(0, 6);
                    const center = numero.substring(6, 10);
                    const fim = numero.substring(numero.length - 4);
                    retorno = `${ini} ${center}-${fim}`;
                } else if (numero.length === 13) {
                    numero = numero.replace(/-/g, '');
                    const ini = numero.substring(0, 5);
                    const center = numero.substring(5, 9);
                    const fim = numero.substring(numero.length - 4);
                    retorno = `${ini} ${center}-${fim}`;
                }
                return retorno;
            }
            export function phone_mask(number: string, add_code_country: boolean = false): string
            {
                number = number ? number.replace(/\D/g, '') : ``;
                let ddd = ``;
                let phone = ``;

                // BRAZIL
                    if($_GLOBAL.OBJ?.info?.phone_format == '+55'){
                        if (number.startsWith('55')) {
                            number = number.slice(2); // remove o código do país se estiver presente
                        }
                    
                        if (number.length < 10 || number.length > 11) return number;
                    
                        ddd = number.slice(0, 2);
                        const rest = number.slice(2);
                    
                        if (rest.length === 9) {
                            phone = `${rest.slice(0, 1)} ${rest.slice(1, 5)}-${rest.slice(5)}`; // celular
                        } else {
                            phone = `${rest.slice(0, 4)}-${rest.slice(4)}`; // fixo
                        }
                    }
                // BRAZIL

                // USA
                    if($_GLOBAL.OBJ?.info?.phone_format == '+1'){
                        if (number.startsWith('1')) {
                            number = number.slice(1); // remove o código do país se estiver presente
                        }
                  
                        if (number.length !== 10) return number;
                  
                        const area = number.slice(0, 3);
                        const exchange = number.slice(3, 6);
                        const subscriber = number.slice(6);
                  
                        phone = `${exchange}-${subscriber}`;
                        ddd = area;
                    }
                // USA
            
                return `${add_code_country ? $_GLOBAL.OBJ?.info?.phone_format : ''}(${ddd}) ${phone}`;
            }
            
            export function phone_ddd(numero?: string): string
            {
                if (!numero) return '';
                const match = numero.match(/\((.*?)\)/);
                return match ? match[1] : '';
            }
            
            export function phone_number(numero?: string): string
            {
                if (!numero) return '';
                const parts = numero.split(')');
                let retorno = parts[1] ? parts[1] : parts[0];
                retorno = retorno.trim().replace(/ /g, '').replace(/-/g, '');
                return retorno;
            }
            
            export function phone_complete(numero?: string): string
            {
                if (!numero) return '';
                let retorno = phone_ddd(numero) + phone_number(numero);
                retorno = retorno.replace(/ /g, '');
                return retorno;
            }
        // PHONE        
    // NUMBERS




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // UPLOAD
        // FORMDATA__
            export function FormData__(obj: any, form: FormData | null = null, namespace: string | null = null): FormData
            {
                const $FormData = form || new FormData();
                var formKey;

                for(var key in obj){
                    if (obj.hasOwnProperty(key)){
                        // FILES
                            if (key == `image` || compare__(`image_`, key)){
                                $_GLOBAL.FORM.v?.[key]?.forEach((file: File, index: number) => {
                                    $FormData.append(`${key}[${index}]`, file);
                                });
                            }
                        // FILES

                        // ELSE
                            else {
                                if (namespace) {
                                    formKey = `${namespace}[${key}]`;
                                } else {
                                    formKey = key;
                                }

                                if (typeof obj[key] === "object") {
                                    if (Array.isArray(obj[key])) {
                                        for (var i = 0; i < obj[key].length; i++) {
                                            FormData__(obj[key][i], $FormData, formKey + "[" + i + "]");
                                        }
                                    } else {
                                        FormData__(obj[key], $FormData, formKey);
                                    }
                                } else {
                                    $FormData.append(formKey, obj[key]);
                                }
                            }
                        // ELSE
                    }
                }
            
                return $FormData;
            }
        // FORMDATA__


        // VERIFY
            export function upload_verify(file: File, type: string = `image`): boolean
            {
                // IMAGE
                    if (type == `image` && file.size > $_GLOBAL.OBJ?.__GLOBAL__?.__MAX_UPLOAD_IMAGE__){
                        alerts(0, `A imagem contém ${(file.size/1000000).toFixed(2)}MB, deve ser menor que ${eval($_GLOBAL.OBJ?.__GLOBAL__.__MAX_UPLOAD_IMAGE__)/1000000}MB!`);
                        return false;
                    }
                // IMAGE

                // FILE
                    else if (file.size > $_GLOBAL.OBJ?.__GLOBAL__?.__MAX_UPLOAD_FILES__){
                        alerts(0, `A imagem contém ${(file.size/1000000).toFixed(2)}MB, deve ser menor que ${eval($_GLOBAL.OBJ?.__GLOBAL__.__MAX_UPLOAD_FILES__)/1000000}MB!`);
                        return false;
                    }
                // FILE

                return true;
            }
        // VERIFY


        // FILES
            export function upload_files(event: Event, name: string): void
            {
                $_GLOBAL.FORM.v[name] = [];

                const target = event.target as HTMLInputElement | null;
                if (target && target.files) {
                    const files = target.files;
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];

                        if (upload_verify(file)) {
                            $_GLOBAL.FORM.v[name].push(file);
                        }
                    }

                    // Resetando o valor do input
                    target.value = '';
                }
            }
        // FILES


        // IMG_BLOB
            export function img_blob(file: any, $original: boolean = false): string
            {
                // VERIFY URL
                    if(is_url(file)){
                        return file;
                    }
                    if(is_json(file)){
                        file = json_decode(file);
                    }

                    if(file?.image){
                        file = $original ? file.image__ : file?.image;

                        if(is_url(file)){
                            return file;
                        }
                    }
                // VERIFY URL

                try {
                    return URL.createObjectURL(file);
                    
                } catch (error) {
                    return ``;
                }
            }
        // IMG_BLOB


        // IMG_BASE64
            export async function img_base64(file: File): Promise<string | ArrayBuffer | null>
            {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = (error) => reject(error);
                });  
            }
        // IMG_BASE64
    // TREATMENT




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // IS / IN
        // IS_IMG
            export function is_img(url: string): boolean
            {
                if (!url || typeof url !== 'string') {
                    return false;
                }

                // Verifica se é uma URL válida
                if (!is_url(url)) {
                    return false;
                }

                // Lista de extensões de imagem
                const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg', '.ico', '.bmp', '.tiff', '.tif'];
                
                // Verifica se a URL termina com extensão de imagem
                const lowerUrl = lower(url);
                for (const ext of imageExtensions) {
                    if (lowerUrl.endsWith(ext)) {
                        return true;
                    }
                }

                // Verifica se contém parâmetros de imagem (como ?width=, ?height=, etc.)
                if (lowerUrl.includes('image') || lowerUrl.includes('img')) {
                    return true;
                }

                // Verifica se contém padrões comuns de URLs de imagem
                const imagePatterns = [
                    /\.(jpg|jpeg|png|gif|webp|svg|ico|bmp|tiff|tif)(\?|$)/i,
                    /\/image\//i,
                    /\/img\//i,
                    /image\.php/i,
                    /img\.php/i
                ];

                for (const pattern of imagePatterns) {
                    if (pattern.test(url)) {
                        return true;
                    }
                }

                return false;
            }
        // IS_IMG


        // IS_FILE
            export function is_file(url: string | File): boolean
            {
                const url_ = url as any;
                if(!url_?.type){
                    return false;
                }

                // Se for um objeto File
                if (url instanceof File) {
                    return true;
                }

                // Se for string (URL)
                if (!url || typeof url !== 'string') {
                    return false;
                }

                // Verifica se é uma URL válida
                if (!is_url(url)) {
                    return false;
                }

                // Lista de extensões de arquivo
                const fileExtensions = [
                    '.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx',
                    '.txt', '.rtf', '.csv', '.zip', '.rar', '.7z', '.tar', '.gz',
                    '.mp3', '.mp4', '.avi', '.mov', '.wmv', '.flv', '.mkv',
                    '.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg', '.ico', '.bmp', '.tiff', '.tif'
                ];
                
                // Verifica se a URL termina com extensão de arquivo
                const lowerUrl = lower(url);
                for (const ext of fileExtensions) {
                    if (lowerUrl.endsWith(ext)) {
                        return true;
                    }
                }

                return false;
            }
        // IS_FILE


        // IS_IFRAME
            export function is_iframe(): boolean
            {
                return !!window.frameElement;
            }
        // IS_IFRAME


        // IS_HEX
            export function is_hex(str: string): boolean
            {
                const hexRegex = /^[0-9a-fA-F]+$/;
                return hexRegex.test(str);
            }
        // IS_HEX


        // IS_STRING
            export function is_string(value: unknown): boolean
            {
                return typeof value === 'string';
            }
        // IS_STRING


        // IS_JSON
            export function is_json(string: string): boolean
            {
                try {
                    const parsed = JSON.parse(string);
                    return typeof parsed === 'object' && parsed !== null;
                } catch (error) {
                    return false;
                }
            }
        // IS_JSON


        // IS_URL
            export function is_url(string: string): boolean
            {
                const pattern = new RegExp('^(https?:\\/\\/)?' + // Protocolo (http ou https)
                    '(([a-zA-Z0-9_-]+\\.)+[a-zA-Z]{2,}|localhost)' + // Domínio ou localhost
                    '(\\:\\d+)?(\\/[-a-zA-Z0-9%_.~+&:]*)*' + // Caminho
                    '(\\?[;&a-zA-Z0-9%_.~+=-]*)?' + // Query string
                    '(\\#[-a-zA-Z0-9_]*)?$', 'i'); // Fragmento
                return pattern.test(string);
            }
        // IS_URL

        
        // IS_ARRAY
            export function is_array(value: unknown): boolean
            {
                return Array.isArray(value);
            }
        // IS_ARRAY
        

        // IN_ARRAY
            export function in_array<T>(value: T, list: unknown): boolean {
                if (Array.isArray(list)) {
                    return list.includes(value);
                }
                if (list && typeof (list as any)[Symbol.iterator] === 'function') {
                    return [...list as Iterable<T>].includes(value);
                }
                return false;
            }
        // IN_ARRAY


        // IN_ARRAY_KEY
            export function in_array_key<T extends string | number>(value: T, list: unknown, ): boolean {
                if (Array.isArray(list)) {
                    return list.includes(value as any);
                }

                if (list instanceof Set) {
                    return list.has(value);
                }

                if (list && typeof list === 'object') {
                    const key = String(value);
                    return !!(list as Record<string, boolean>)[key]; // true → selecionado
                }

                return false;
            }
        // IN_ARRAY_KEY
        
        // COUNT_ARRAY
            export function count_array(array: unknown[]): boolean
            {
                return Array.isArray(array) && array.length > 0;
            }
        // COUNT_ARRAY


        // IS_OBJECT
            export function is_object(value: unknown): boolean
            {
                return value !== null && typeof value === 'object';
            }
        // IS_OBJECT


        // IN_OBJECT
            export function in_object(value: unknown, object: Record<string, unknown>): boolean
            {
                return Object.values(object).includes(value);
            }
        // IN_OBJECT


        // IN_OBJECT
            export function in_object_key(key: string, object: Record<string, unknown>): boolean
            {
                return key in object;
            }
        // IN_OBJECT


        // IS_E
            export function is_e($e: any, $all = 0): Element | NodeListOf<Element> | string
            {
                let result: any = ``;

                if ($e.classList != null){
                    result = $e;
                } else if ($all){
                    result = document.querySelectorAll($e);
                    if (!result[0]){
                        result = '';
                    }
                } else {
                    result = document.querySelector($e);
                }

                return result;
            }
        // IS_E


        // IS_NUMBER
            export function is_number(value: any): boolean
            {
                return !isNaN(parseFloat(value)) && isFinite(value);
            }
        // IS_NUMBER
    // IS




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // LOAD
        // LOAD
            export function load(load: number | boolean = 1): void
            {
                document.querySelectorAll(`.__LOAD__${load}`).forEach(item => {
                    (item as HTMLElement).style.display = 'block';
                });
            }
            export function load_time(load__: number | boolean = 1, time: number = 200): void
            {
                load(load__);
                setTimeout(() => {
                    load_close();
                }, time);
            }
        // LOAD


        // LOAD_CLOSE
            export function load_close(): void
            {
                for (let i = 1; i <= 10; i++) {
                    document.querySelectorAll(`.__LOAD__${i}`).forEach(item => {
                        (item as HTMLElement).style.display = 'none';
                    });
                }
                document.querySelectorAll(`.load_branco`).forEach(item => {
                    (item as HTMLElement).style.display = 'none';
                });
            }
            export function load_close_0()
            {
                document.querySelectorAll(`.__LOAD__0`).forEach(item => {
                    (item as HTMLElement).style.display = 'none';
                });
            }

        // LOAD_CLOSE
    // LOAD




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // USEFUL
        // FILTER__
            export function filter__<T extends Record<string, any>>(array: T[], id: string | number, column: string = 'name', column_id: string = 'id' ): string
            {
                if (!Array.isArray(array) || array.length === 0) {
                    return '';
                }
            
                const idStr = String(id);
            
                for (const item of array) {
                    // Garante que a chave existe antes de comparar
                    if (item != null && Object.prototype.hasOwnProperty.call(item, column_id)) {
                        if (String(item[column_id]) == idStr) { // comparação frouxa estilo PHP ==
                            if (Object.prototype.hasOwnProperty.call(item, column) && item[column] != null) {
                                return String(item[column]);
                            }
                            return '';
                        }
                    }
                }
            
                return '';
            }
        // FILTER__


        // PAGES_SIGN
            export function pages_sign(): boolean
            {
                let pages = ['login', 'sign_up', 'forget_password', 'error404'];
                if (in_array($_GET['PG'], pages)) {
                    return true;
                }
                return false;
            }
        // PAGES_SIGN


        // SQL_JSON_TRUE
            export function sql_json_true(input: string | Record<string, unknown> | unknown[]): Record<string, true> | true[] {
                let data: unknown = {};

                // STRING  ➜  tentar JSON.parse
                if (typeof input === 'string') {
                    try {
                        const decoded = JSON.parse(input);
                        if (decoded !== null && (typeof decoded === 'object' || Array.isArray(decoded))) {
                            data = decoded;
                        }
                    } catch {
                        data = {};
                    }
                }

                // ARRAY
                else if (Array.isArray(input)) {
                    data = input;
                }

                // OBJECT
                else if (input !== null && typeof input === 'object') {
                    data = input;
                }

                // ELSE ➜ permanece {}

                // ========= FILTRO =========
                if (Array.isArray(data)) {
                    // Retorna apenas os elementos cujo valor seja true / "true"
                    return (data as unknown[]).filter(
                        v => v === true || v === 'true'
                    ) as true[];
                }

                const result: Record<string, true> = {};
                for (const [key, value] of Object.entries(data as Record<string, unknown>)) {
                    if (value === true || value === 'true') {
                        result[key] = true;
                    }
                }

                return result;
            }
        // SQL_JSON_TRUE


        // SQL_JSON_LIST
            export function sql_json_list(input: Record<string, unknown> | unknown[]): string[]
            {
                if (Array.isArray(input)) {
                    return input.reduce<string[]>((keys, value, index) => {
                    if (value === true || value === 'true') keys.push(String(index));
                    return keys;
                    }, []);
                }

                if (input && typeof input === 'object') {
                    return Object.keys(input).filter(
                    (key) => input[key] === true || input[key] === 'true'
                    );
                }
                return [];
            }
        // SQL_JSON_LIST


        // MB_STRLEN
            export function mb_strlen(text: unknown): number
            {
                text = String(text);
                if (typeof text !== 'string'){
                    return 0;
                }
                return [...text].length;
            }
        // MB_STRLEN


        // IFRAME_SEND_FATHER
            export function iframe_send_father(content: unknown): boolean
            {
                return (window as any).parent.receiveFromIframe__(content);
            }
        // IFRAME_SEND_FATHER


        // IFRAME_RESPONSE_SELECT
            export function iframe_response_select(response: any): void
            {
                const optionEl = document.createElement('option');
                optionEl.value = response.id;
                optionEl.text = response.name;

                const select = document.querySelector(`select[name="${$_GLOBAL.SHOW.iframe_array?.name}"]`) as HTMLSelectElement;
                if (select) {
                    // VERIFICA SE A OPÇÃO JÁ EXISTE (PELO VALUE)
                        const exists = Array.from(select.options).some(opt => opt.value == response.id);

                        if (!exists) {
                            select.appendChild(optionEl);
                        }
                    // VERIFICA SE A OPÇÃO JÁ EXISTE (PELO VALUE)

                    // ORDENA TODAS AS OPÇÕES POR TEXTO (NOME)
                        const sortedOptions = Array.from(select.options).sort((a, b) => {
                            if (a.value === '' && b.value !== '') return -1;
                            if (b.value === '' && a.value !== '') return 1;
                            return a.text.localeCompare(b.text);
                        });
                    // ORDENA TODAS AS OPÇÕES POR TEXTO (NOME)

                    // LIMPA E REINSERE ORDENADOS
                        select.innerHTML = '';
                        sortedOptions.forEach(opt => select.appendChild(opt));
                    // LIMPA E REINSERE ORDENADOS

                    // SET VALUE
                        select2_reset(`[name="${$_GLOBAL.SHOW.iframe_array?.name}"]`);
                        setTimeout(() => {
                            $_GLOBAL.FORM.v[$_GLOBAL.SHOW.iframe_array?.name] = response.id;
                        }, 50);
                    // SET VALUE
                }
            }
        // IFRAME_RESPONSE_SELECT


        // STRIP_TAGS__
            export function strip_tags__(input: string): string
            {
                input = replace(`-->HTML->`, ``, input);
                const doc = new DOMParser().parseFromString(input, 'text/html');
                return doc.body.textContent || '';
            }
        // STRIP_TAGS__


        // KEY__
            export function key__(array: Record<string, unknown> | unknown[]): string[]
            {
                return Object.keys(array);
            }
        // KEY__
        
        
        // PERC_3
            export function perc_3(number: number, number_total: number, response_in_perc: number = 1, negative: number = 0): number
            {
                let response;
                if (response_in_perc){
                    response =  (number * 100) / number_total
                } else {
                    response =  (number * number_total) / 100
                }

                if (negative){
                    return response;
                } else {
                    return Math.min(response, 100);
                }
            }
        // PERC_3


        // REVERSE
            export function reverse<T>(array: T[]): T[] {
                return [...(array || [])].reverse();
            }
        // REVERSE


        // CLEAR
            export function clear(key: number, count: number, className: string = ''): string
            {
                key = key + 1;
                return (key % count === 0) ? `<div class="clear ${className}"></div>` : '';
            }
            export function clear_li(key: number, count: number, className: string = ''): string
            {
                key = key + 1;
                return (key % count === 0) ? `<li class="clear ${className}"></li>` : '';
            }
        // CLEAR


        // LOCATION
            export function location(url: string): void
            {
                window.location.href = url;
            }
        // LOCATION

        
        // CLONE
            export function clone<T>(value: T): T
            {
                // if (typeof value === 'object' && value !== null) {
                if (value !== null) {
                    return JSON.parse(JSON.stringify(value));
                }
                return value;
            }
        // CLONE


        // FORM_SUBMIT
            export function form_submit(event: Event): void
            {
                const textarea = event.target as HTMLElement | null;

                if (textarea) {
                    const form = textarea.closest('form');
                    if (form) {
                        const button = form.querySelector('button') as HTMLButtonElement | null;
                        button?.click();
                    }
                }
            }
        // FORM_SUBMIT


        // MARKDOWN
            export function markdown(markdown: string): string
            {
                // STANDARDIZE LINE BREAKS TO AVOID PROBLEMS
                    markdown = markdown.replace(/\n\n/g, "<br>\n");
                    markdown = markdown.replace(/\r\n|\r/g, "\n");
                // STANDARDIZE LINE BREAKS TO AVOID PROBLEMS


                // TITLE
                    markdown = markdown.replace(/^######\s*(.+)$/gm, "<h6>$1</h6>");
                    markdown = markdown.replace(/^#####\s*(.+)$/gm, "<h5>$1</h5>");
                    markdown = markdown.replace(/^####\s*(.+)$/gm, "<h4>$1</h4>");
                    markdown = markdown.replace(/^###\s*(.+)$/gm, "<h3>$1</h3>");
                    markdown = markdown.replace(/^##\s*(.+)$/gm, "<h2>$1</h2>");
                    markdown = markdown.replace(/^#\s*(.+)$/gm, "<h1>$1</h1>");
                // TITLE
    

                // IMAGES
                    markdown = markdown.replace(/!\[(.*?)\]\((.+?)\s*("(.*?)")?\)/g, '<img src="$2" alt="$1" title="$4" />');
                // IMAGES


                // TABLES
                    markdown = markdown.replace(/^\|(.+?)\|\n\|[-| ]+\|\n((?:\|.+?\|\n)+)/gm, (match, headers, rows) => {
                        const headerHtml = headers.split('|').map((h: string) => `<th>${h.trim()}</th>`).join('');
                        const rowsHtml = rows
                            .trim()
                            .split('\n')
                            .map((row: string) => {
                                const cols = row
                                    .split('|')
                                    .filter((col: string) => col.trim() !== '')
                                    .map((col: string) => `<td>${col.trim()}</td>`)
                                    .join('');
                                return `<tr>${cols}</tr>`;
                            })
                            .join('');
                        return `<table><thead><tr>${headerHtml}</tr></thead><tbody>${rowsHtml}</tbody></table>`;
                    });
                // TABLES
            

                // CHECKBOXES
                    markdown = markdown.replace(/- \[x\] (.+)/g, '<div class="checkbox"><input type="checkbox" checked disabled /> $1</div>');
                    markdown = markdown.replace(/- \[ \] (.+)/g, '<div class="checkbox"><input type="checkbox" disabled /> $1</div>');
                // CHECKBOXES


                // ORDERED LISTS
                    // markdown = markdown.replace(/^(\d+)\.\s(.+)$/gm, "<ol><li>$1. $2</li></ol>");
                    // markdown = markdown.replace(/<\/ol>\s*<ol>/g, ""); // Remove duplicadas de <ol>
                    markdown = markdown.replace(/^(\d+)\.\s(.+)$/gm, '<div class="ol">$1. $2</div>');
                // ORDERED LISTS


                // UNORDERED LISTS
                    markdown = markdown.replace(/^\*\s(.+)$/gm, "<ul><li>$1</li></ul>");
                    markdown = markdown.replace(/<\/ul>\s*<ul>/g, ""); // Remove duplicadas de <ul>
                // UNORDERED LISTS
            

                // BOLD AND ITALIC
                    markdown = markdown.replace(/\*\*\*(.+?)\*\*\*/g, "<strong><em>$1</em></strong>"); // Negrito e itálico
                    markdown = markdown.replace(/\*\*(.+?)\*\*/g, "<strong>$1</strong>"); // Negrito
                    markdown = markdown.replace(/\*(.+?)\*/g, "<em>$1</em>"); // Itálico
                // BOLD AND ITALIC
            

                // LINKS
                    markdown = markdown.replace(/\[(.+?)\]\((.+?)\)/g, '<a href="$2" class="c_blue link" target="_blank">$1</a>');
                // LINKS
    

                // CODE BLOCKS
                    markdown = markdown.replace(/```([a-zA-Z]*)\n([\s\S]*?)```/g, (match, lang, code) => {
                        const languageClass = lang ? `class="${lang}"` : "";
                        return `<pre><code ${languageClass}>${code.trim()}</code></pre>`;
                    });
                // CODE BLOCKS


                // INLINE CODE
                    markdown = markdown.replace(/`(.+?)`/g, "<code>$1</code>");
                // INLINE CODE
    

                // SEPARATE LINES INTO PARAGRAPHS, EXCEPT LINES THAT ALREADY HAVE HTML
                    const lines = markdown.split("\n");
                    let html = lines
                        .map(line => {
                            const trimmedLine = line.trim();
                            // Se a linha já contém uma tag HTML, não adiciona <p>
                            if (/^<.*>.*<\/.*>$/.test(trimmedLine) || /^<.*\/>$/.test(trimmedLine)) {
                                return trimmedLine;
                            }
                            return trimmedLine ? `${trimmedLine}<br />` : "";
                        })
                        .filter(line => line !== "<p></p>") // Remove parágrafos vazios
                        .join("");
                // SEPARATE LINES INTO PARAGRAPHS, EXCEPT LINES THAT ALREADY HAVE HTML


                // BREAK
                    html = replace(`{-BREAK-}`, "<br>", html);
                // BREAK

                if(html.trim() == ``){
                    return ``;
                }

                return `<div class="__MARKDOWN__">${html}</div>`;
            }
        // MARKDOWN


        // HELLO
            export function hello(): string
            {
                const horaAtual = new Date().getHours();
                if (horaAtual >= 0 && horaAtual < 12) {
                    return "Bom dia";
                } else if (horaAtual >= 12 && horaAtual < 18) {
                    return "Boa tarde";
                } else {
                    return "Boa noite";
                }
            }
        // HELLO


        // NL2BR
            export function nl2br($text: string): string
            {
                return $text.replace(/\n/g, '<br>');
            }
        // NL2BR


        // GENDER
            export function gender(value: string): number | null
            {
                if (typeof value !== 'string') return null;
                value = value.toLowerCase().trim();
                const femininas = ['a', 'as', 'ona', 'onas', 'ina', 'inas', 'essa', 'essas', 'eta', 'etas', 'isa', 'isas', 'eira', 'eiras', 'triz', 'trizes', 'ã', 'ãs', 'gem', 'gens', 'ice', 'ez', 'ezas', 'eza', 'ona', 'onas', 'ães', 'ura', 'ias', 'lha', 'lhas', 'tude', 'ade', 'ude', 'ória', 'écia', 'ância', 'ência', 'idade', 'ilidade', 'sse', 'gueira', 'rte', 'lhas', 'ela', 'uras', 'elas'];
                for (const fim of femininas) {
                    if (value.endsWith(fim)) {
                        return 0;
                    }
                }
                return 1;
            }
        // GENDER


        // CHECK_URL
            export async function check_url(url: string): Promise<boolean>
            {
                try {
                    const response = await fetch(url, { method: "HEAD" });
                    return response.ok;

                } catch (error) {
                    console.error("URL verification failed:", error);
                    return false;
                }
            }
        // CHECK_URL


        // URL_HISTORY
            // export function url_history(url: string): void
            // {
            //     window.history.pushState({}, '', `${DIR()}${url}`);
            //     // $_GLOBAL.ROUTER.push({ path: url, query: data });
            // }
            // export function url_change(url: string): void
            // {
            //     url_history(url);
            // }
        // URL_HISTORY


        // BACK
            export function back(): void
            {
                window.history.back();
            }
        // BACK


        // SCROLL_HEIGHT
            export function scroll_height($class: string, $show__value_1: string, $value_2 = ``): void
            {
                setTimeout(() => { 
                    const box = document.querySelector($class);
                    if (box) {
                        const windowHeight = window.innerHeight;
                        const contentHeight = box.scrollHeight;
                        
                        if (contentHeight > windowHeight) {
                            $_GLOBAL.SHOW[$show__value_1] = $value_2;
    
                        } else {
                            $_GLOBAL.SHOW[$show__value_1] = $show__value_1;
                        }
                    }
                }, .5)    
            }
        // SCROLL_HEIGHT


        // CARD_VALIDATE
            export function card_validate(card_number: string, card_validate: string,): boolean | { error: string }
            {
                // CARD_NUMBER
                    let card_number__ = card_number.replace(/[^0-9]/g, "");

                    if (card_number__.length < 13 || card_number__.length > 19) {
                        return { error: `O número do cartão não é válido!` };
                    }

                    if (card_number__ === '0000000000000000') {
                        return { error: `O número do cartão não é válido!` };
                    }

                    let soma = 0;
                    card_number__.split("").reverse().forEach((char, i) => { 
                        let n = parseInt(char, 10);
                        if (isNaN(n)) return;
                        if (i % 2 === 1) { 
                            n *= 2; 
                            if (n > 9) { 
                                n -= 9; 
                            } 
                        } 
                        soma += n; 
                    });

                    if (!(soma % 10 === 0)){
                        return { error: `O número do cartão não é válido!` };
                    }
                // CARD_NUMBER

                // CARD_EXPIRATION
                    let currentDate = new Date();
                    let currentYear = currentDate.getFullYear();
                    let currentMonth = currentDate.getMonth() + 1;

                    let $ex = explode('/', card_validate);
                    let card_expiration_month = $ex[0];
                    let card_expiration_year = parseInt($ex[1]) >= 2000 ? parseInt($ex[1]) : 2000 + parseInt($ex[1])

                    let card_expiration__ = `${card_expiration_month}/${card_expiration_year}`;
                    let ex = card_expiration__.split('/');
                    if (ex.length === 2) {
                        let mes = parseInt(ex[0], 10);
                        let ano = parseInt(ex[1], 10);

                        if (ano < currentYear || (ano === currentYear && mes < currentMonth)) {
                            return { error: `A validade informada já está vencida!` };
                        }

                        if (!isNumeric(ano) || ano.toString().length !== 4) {
                            return { error: `O ano de expiração do cartão deve ser um número de 4 dígitos!` };
                        }
                        if (!isNumeric(mes) || mes < 1 || mes > 12) {
                            return { error: `O mês de expiração do cartão deve ser um número entre 1 e 12!` };
                        }
                    } else {
                        const validade = card_expiration__.replace(/[^0-9]/g, "");
                        if (validade.length !== 6) {
                            return { error: `Formato de vencimento inválido!` };
                        }
                    }
                // CARD_EXPIRATION

                return true;
            }
            export function isNumeric(value: any): boolean
            {
                return !isNaN(parseFloat(value)) && isFinite(value);
            }
        // CARD_VALIDATE


        // CARD_BRAND
            const cardBrands: { [key: string]: RegExp } = {
                visa: /^4/,
                mastercard: /^(5[1-5]|2[2-7])/,
                amex: /^3[47]/,
                diners: /^3(?:0[0-5]|[68])/,
                discover: /^6(?:011|5)/,
                jcb: /^(?:2131|1800|35\d{3})/,
                elo: /^(4011|4312|4389|4514|4576|5041|5066|509|6277|6363|650|6516|6550)/,
                hipercard: /^(606282|3841)/,
            };
            export function card_brand(card_number: string = `card_number`, card_brand: string = `card_brand`): void
            {
                try {                    
                    const sanitizedNumber = $_GLOBAL.FORM.v[card_number].replace(/\D/g, ''); // Remove caracteres não numéricos
                    $_GLOBAL.FORM.v[card_brand] = null; // Reseta a bandeira
                    for(const [brand, regex] of Object.entries(cardBrands)){
                        if (regex.test(sanitizedNumber)){
                            $_GLOBAL.FORM.v[card_brand] = brand.toLowerCase(); // Define a bandeira em maiúsculo
                            break;
                        }
                    }
                } catch (error) {}
            };
        // CARD_BRAND


        // COOKIES
            export function COOKIES(name: string): string
            {
                const nomeEQ = name + "=";
                const cookies = document.cookie.split(';');
                for(let i = 0; i < cookies.length; i++) {
                  let c = cookies[i];
                  while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                  if (c.indexOf(nomeEQ) === 0) return c.substring(nomeEQ.length, c.length);
                }
                return ``;
            }
            export function isset_COOKIES(name: string): boolean
            {
                return (COOKIES(name) && COOKIES(name) != '' && COOKIES(name) != 'null') ? true : false;
            }
            export function COOKIES_CREATE(name: string, value: string, days = 0): void
            {
                let expires = "";
                if (days) {
                  const date = new Date();
                  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                  expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "")  + expires + "; path=/";
            }
            export function COOKIES_LIST(): any[]
            {
                return document.cookie
                    .split('; ')
                    .filter(Boolean)
                    .map(entry => {
                        const [name, ...rest] = entry.split('=');
                        return {
                            name:  decodeURIComponent(name),
                            value: decodeURIComponent(rest.join('='))
                        };
                    });
            }
            export function COOKIES_DELETE(name: string): void
            {
                document.cookie = name + '=; Max-Age=0; path=/;';
            }
        // COOKIES


        // COUNT
            export function count(array: any[] | Record<string, any>): boolean
            {
                if (is_array(array)){
                    return array.length > 0;
                }
                if (is_object(array)){
                    return Object.keys(array).length > 0;
                }
                return false;
            }
            export function count__(array: unknown[] | Record<string, unknown>): number
            {
                return count_x(array);
            }
            export function count_x(array: any[] | Record<string, any>): number
            {
                if (is_array(array)){
                    return array.length;
                }
                if (is_object(array)){
                    return Object.keys(array).length;
                }
                return 0;
            }
        // COUNT


        // TOKEN / CODE
            export function token(tamanho = 10, letra = true, numeros = true, simbolos = false): string
            {
                const char = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                // const char = 'abcdefghijklmnopqrstuvwxyz';
                const num = '12345678901234567890';
                const simb = '!@#$&*';
                let retorno = '';
                let caracteres = '';
                
                if (letra) caracteres += char;
                if (numeros) caracteres += num;
                if (simbolos) caracteres += simb;
                
                const len = caracteres.length;
                
                for (let n = 1; n <= tamanho; n++) {
                    const rand = Math.floor(Math.random() * len);
                    retorno += caracteres.charAt(rand);
                }
                
                return retorno;
            }
            export function code(tamanho = 10, letra = true, numeros = true, simbolos = false): string
            {
                return token(tamanho, letra, numeros, simbolos);
            }
        // TOKEN / CODE


        // JSON_
            export function json_encode(value: any, $type = ``): string
            {
                if ($type == 'array'){ value = Object.entries(value); }
                if ($type == 'object'){ { value = {...value} }; }
                return JSON.stringify(value);
            }
            export function json_decode(json: string, $type = ``): unknown
            {
                try {
                    if (json){
                        let value = JSON.parse(json);
                        if ($type == 'array'){ value = Object.entries(value); }
                        if ($type == 'object'){ value = {...value}; }
                        return value;
                    }
                    return ``;
                        
                } catch (error) {
                    return ``;                    
                }
            }
        // JSON_


        // SPLIT | COMPARE__
            export function split(match: string, value: string): boolean
            {
                try {
                    if (value && value.split(match)[1] != null){
                        return true;
                    }
                } catch (error) { }
                return false;
            }

            export function explode(match: string, value: string): string[]
            {
                return value ? value.split(match) : [];
            }
            export function ex(match: string, value: string): string[]
            {
                return value ? value.split(match).map(item => item.trim()).filter(Boolean) : [];
            }

            export function compare__(match: string, value: string): boolean
            {
                return split(match, value);
            }
            export function compare__ini(match: string, value: string): boolean
            {
                return value.includes(match);
            }
            export function compare__fim(match: string, value: string): boolean
            {
                return value.endsWith(match);
            }
        // SPLIT | COMPARE__


        // REQUEST OBJ / PAGG
            export function request_obj(request: any): void
            {
                // SHOW
                    for(let key in request?.SHOW){
                        let array = request.SHOW[key];

                        // VALUE
                            if (key) {
                                $_GLOBAL.SHOW[key] = array;
                            }
                        // VALUE

                    }
                // SHOW


                // OBJ
                    for(let key in request?.OBJ){
                        let array = request.OBJ[key];

                        // VALUE
                            if (key) {
                                $_GLOBAL.OBJ[key] = array;
                            }
                        // VALUE


                        // DATATABLE
                            if (array?.data && array?.links){
                                $_GLOBAL.OBJ[key] = array.data;

                                // PAGG
                                    array.data = [];

                                    // LINKS
                                        let $next;
                                        let value_1;
                                        const $links: any[] = [];
                                        for(const linkKey in array.links){
                                            let value = array.links[linkKey];

                                            $next = MOBILE() ? 0 : 1;

                                            if (value.label == `&laquo; Anterior`){
                                                value.label = `<`;
                                                value.url = array?.links?.[1]?.url ? array.links[1].url : ``;
                                                $next = 1;
                                            }
                                            if (value.label == `Próxima &raquo;`){
                                                value.label = `>`;
                                                value.url = array?.links?.[+linkKey - 1]?.url ? array.links[+linkKey - 1].url : ``;
                                                $next = 1;
                                            }
                                            if (value.label == `...` && !MOBILE()){
                                                $next = 1;
                                            }

                                            // MOBILE
                                            if (MOBILE() && value.active == true){
                                                // PREV
                                                    value_1 = array.links[parseInt(linkKey)-3];
                                                    if (value_1 && value_1.label != `<` && value_1.label != `...` && value_1.label != `>` && value_1.label != `&laquo; Anterior` && value_1.label != `Próxima &raquo;`){
                                                        value_1.url = replace(DIR_API(), ``, value_1.url);
                                                        value_1.label = '...';
                                                        $links.push(value_1);
                                                    }
                                                    value_1 = array.links[parseInt(linkKey)-2];
                                                    if (value_1 && value_1.label != `<` && value_1.label != `...` && value_1.label != `>` && value_1.label != `&laquo; Anterior` && value_1.label != `Próxima &raquo;`){
                                                        value_1.url = replace(DIR_API(), ``, value_1.url);
                                                        $links.push(value_1);    
                                                    }
                                                    value_1 = array.links[parseInt(linkKey)-1];
                                                    if (value_1 && value_1.label != `<` && value_1.label != `...` && value_1.label != `>` && value_1.label != `&laquo; Anterior` && value_1.label != `Próxima &raquo;`){
                                                        value_1.url = replace(DIR_API(), ``, value_1.url);
                                                        $links.push(value_1);    
                                                    }
                                                // PREV

                                                // CURRENT
                                                    value.url = replace(DIR_API(), ``, value.url);
                                                    $links.push(value);
                                                // CURRENT
        
                                                // NEXT
                                                    value_1 = array.links[parseInt(key)+1];
                                                    if (value_1 && value_1.label != `<` && value_1.label != `...` && value_1.label != `>` && value_1.label != `&laquo; Anterior` && value_1.label != `Próxima &raquo;`){
                                                        value_1.url = replace(DIR_API(), ``, value_1.url);
                                                        $links.push(value_1);    
                                                    }
                                                    value_1 = array.links[parseInt(key)+2];
                                                    if (value_1 && value_1.label != `<` && value_1.label != `...` && value_1.label != `>` && value_1.label != `&laquo; Anterior` && value_1.label != `Próxima &raquo;`){
                                                        value_1.url = replace(DIR_API(), ``, value_1.url);
                                                        $links.push(value_1);    
                                                    }
                                                    value_1 = array.links[parseInt(key)+3];
                                                    if (value_1 && value_1.label != `<` && value_1.label != `...` && value_1.label != `>` && value_1.label != `&laquo; Anterior` && value_1.label != `Próxima &raquo;`){
                                                        value_1.url = replace(DIR_API(), ``, value_1.url);
                                                        value_1.label = '...';
                                                        $links.push(value_1);    
                                                    }
                                                // NEXT
                                            }
                                            // MOBILE

                                            if ($next){
                                                value.url = replace(DIR_API(), ``, value.url);
                                                $links.push(value);
                                            }
                                        }
                                    // LINKS

                                    $_GLOBAL.OBJ.PAGG[key] = $links;
                                    $_GLOBAL.OBJ.PAGG[`${key}_all`] = array;
                                // PAGG
                            }
                        // DATATABLE


                        // ITEMS_PAGE
                            if ($_GLOBAL.OBJ.PAGG[`${key}_all`]?.z_items_page){
                                $_GLOBAL.FORM.z_items_page = $_GLOBAL.OBJ.PAGG[`${key}_all`].z_items_page;
                            }
                        // ITEMS_PAGE


                        // ORDER
                            $_GLOBAL.FORM.ORDER = {};
                            for(let key in $_GLOBAL.OBJ?.DATATABLE){
                                $_GLOBAL.FORM.ORDER[$_GLOBAL.OBJ?.DATATABLE[key].id] = $_GLOBAL.OBJ?.DATATABLE[key].order;
                            }
                        // ORDER
                    }
                // OBJ


                // MENU_SIDE
                    // ADMIN
                        if ($_GET[0] == `admin`){
                            admin__menu_side();
                        }
                    // ADMIN
                // MENU_SIDE


                // CART
                    if (request?.OBJ?.shipping_update){
                        if ($_GLOBAL.SHOW?.controller){
                            $_GLOBAL.SHOW.controller.abort();
                        }

                        $_GLOBAL.SHOW.controller = new AbortController();
                        api(`/dashboard/cart/shipping`, { shipping_update: 1 }, (json: any) => {}, 0, 1, 0, 0, $_GLOBAL.SHOW.controller);
                    }
                // CART


                // TITLE
                    if($_GET[0] == `admin`){
                        document.title = `Administração do Sistema`;

                    } else if(LOCALHOST()){
                        if (request?.OBJ?.info?.name_site){
                            document.title = request.OBJ.info.name_site;
                        }
                    }
                // TITLE


                // NEW
                    NEW__request_obj(request);
                // NEW
            }
        // REQUEST OBJ / PAGG
        

        // STRIP TAGS
            export function strip_tags(input: string, allowed: string = ''): string
            {
                input = replace(`&nbsp;`, ` `, input);
                input = input.trim();

                allowed = (((allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('')
                let tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi
                let commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi
                return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1){
                    return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : ''
                })
            }
            function stripHtml(raw: string): string
            {
                return decodeEntities(raw).replace(/<\/?[^>]+>/g, '');
            }
            function decodeEntities(html: string): string
            {
                const map: Record<string, string> = { '&lt;':  '<', '&gt;':  '>', '&amp;': '&', '&quot;': '"', '&#39;': `'` };
                return html.replace(/&lt;|&gt;|&amp;|&quot;|&#39;/g, m => map[m]);
            }
        // STRIP TAGS


        // REPLACE
            export function replace($de: string, $para: string, $txt: string): string
            {
                let result = ``;
                if ($txt){
                    result = $txt;
                    try {
                        if ($txt.indexOf($de) >= 0){
                            let $pos = $txt.indexOf($de);
                            while ($pos > -1){
                                result = result.replace($de, $para);
                                $pos = result.indexOf($de);
                            }
                        }
                    } catch(e) {}
                }
                return result;
            }
        // REPLACE


        // ADDRESS
            interface AddressValue {
                address?: string;
                number?: string;
                complement?: string;
                neighborhood?: string;
                city?: string;
                uf?: string;
                zipcode?: string;
                country?: string;
            }
            export function address_1(value: AddressValue | null): string
            {
                let result = ``;
                result += value?.address;
                result += value?.number ? `, ${value.number} ` : ``;
                result += value?.complement ? value.complement : ``;
                return result;
            }
            export function address_2(value: AddressValue | null): string
            {
                let result = ``;
                result += value?.neighborhood ? `${value.neighborhood}` : ``;
                return result;
            }
            export function address_3(value: AddressValue | null): string
            {
                if (!value || !value.city || !value.uf) return '';
                return `${value.city}/${value.uf}`;
            }
            export function address_4(value: AddressValue | null): string
            {
                return value?.zipcode??``;
            }
        // ADDRESS


        // NAME
            export function name(name: string, $tipo = 0): string // 0-> So nome; 1->So Sobrenome; 2->x qty de nome
            {
                if (name == null){
                    return ``;
                }

                let ex = name.split(' ');

                // NOME
                    if ($tipo == 0){
                        return ex[0];
                    }
                // NOME

                // SOBRENOME
                    if ($tipo == 1){
                        return ex[1]!=null ? ex[1] : ``;
                    }
                // SOBRENOME

                // QTY
                    if ($tipo >= 2){
                        let result = ``;
                        for (let i = 0; i < $tipo; i++) {
                            result += ex[i] ? `${ex[i]} ` : ``;

                            // SO NOME FOR MENOR QUE X VAR
                                if (ex[i] != null && ex[i].length < 4){
                                    i++;
                                    result += (ex[i]!=null && ex[i]) ? `${ex[i]} ` : ``;
                                }
                            // SO NOME FOR MENOR QUE X VAR
                        }
                        result = result.trim();
                        result = replace('-', '', result);
                        result = replace('_', '', result);

                        return result;
                    }
                // QTY

                return ``;
            }

            export function name_ini(name: string): string
            {
                let result = ``;

                if (name != null && name){
                    let ex = name.split(' ');

                    result = upper(ex[0][0]);
                    if (ex[1] != null && ex[1][0] != null){
                        result += upper(ex[1][0]);
                    } else {
                        result += lower(ex[0][1]);
                        result += lower(ex[0][2]);
                    }
                }
                result = replace('-', '', result);
                result = replace('_', '', result);

                return result;
            }
        // NAME


        // LOWER
            export function lower(str: string): string
            {
                if (str){
                    return str.toLowerCase();
                }
                return str;
            }
        // LOWER


        // UPPER
            export function upper(str: string): string
            {
                if (str){
                    return str.toUpperCase();
                }
                return str;
            }
        // UPPER


        // UCFIRST
            export function ucfirst(str: string): string
            {
                if (!str) return str;
                return str.charAt(0).toUpperCase() + str.slice(1);
            }
            export function ucwords(str: string): string
            {
                if (!str) return str;
                return str.replace(/(^|\s|\b)(\p{L})/gu, (match, sep, letter) => sep + letter.toUpperCase());
              }
        // UCFIRST


        // TOP
            export function top(): void
            {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                  });
            }
        // TOP


        // BOTTOM
            export function bottom(className: string = ``): void
            {
                let container: HTMLElement | null;
                if(className){
                    container = document.querySelector(className);
                } else {
                    container = document.body;
                }

                if(container){
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            }
        // BOTTOM


        // IMPLODE
            export function implode(separator: string, value: unknown[] | Record<string, unknown>): string
            {
                if (Array.isArray(value)) {
                    return value.join(separator);
                }

                if (value && typeof value === 'object') {
                    return Object.values(value).join(separator);
                }

                if (typeof value === 'string' || typeof value === 'number') {
                    return String(value);
                }

                return '';
            }
            export function implode_key(separator: string, array: Record<string, unknown>): string
            {
                if (typeof array === "object" && array !== null){
                    const array_temp = array;
                    const keys: string[] = [];
                    for(let key in array_temp){
                        keys.push(key);
                    }
                    return keys.join(separator);
                }
                return '';
            }
        // IMPLODE


        // LIMIT
            export function limit(text: string, limit: number, ellipsis = '...', trim = 1): string
            {
                if (text != null && text.length > limit) {
                    text = text.substr(0, limit);
                    if (trim){
                        text = text.trim();
                    }
                    text += ellipsis;
                }

                return text;
            }
        // LIMIT


        // OPTIONS__
            interface SelectOption {
                id: number | string;
                name: string;
                selected?: boolean;
            }
            export function options__(PROPS: any & { options?: SelectOption[] }, checkbox: boolean = false): SelectOption[]
            {
                let result: SelectOption[] = [];
                let options_extra: any = (Array.isArray(PROPS.options) && PROPS.options.length > 0) ? PROPS.options : (PROPS as any).extra;

                // CREATE ARRAY
                    // YEAR
                        if (compare__(`|->year`, options_extra) || compare__(`|->years`, options_extra)){

                        }
                    // YEAR


                    // MONTH
                        else if (compare__(`|->month`, options_extra)){
                            
                        }
                    // MONTH


                    // |->>
                        else if (compare__(`|->>`, options_extra)){
                            let options_extra__ = options_extra.split('|->>');
                            options_extra = options_extra__[1].split('; ');

                            for(let key in options_extra){
                                let value = options_extra[key];

                                if (value != null){
                                    let ex = value.split(': ');

                                    if (ex[1] != null){
                                        result.push({'id': ex[0], 'name': ex[1]});
                                    } else if (ex[0] != null){
                                        result.push({'id': ex[0], 'name': ex[0]});
                                    }
                                }
                            }
                        }
                    // |->>


                    // IS_ARRAY
                        else if (is_array(PROPS?.options)) {
                            result = PROPS?.options;
                        }
                    // IS_ARRAY


                    // IS_OBJECT
                        else if (is_object(PROPS?.options)) {
                            result = [];
                            const obj = PROPS.options as unknown as Record<string, string>;
                            for (const key of Object.keys(obj)) {
                                result.push({'id': key, 'name': PROPS?.options[key]});
                            }
                            checkbox = false;
                        }
                    // IS_OBJECT


                    // ELSE
                        else if ($_GLOBAL.OBJ?.__QUERYS__?.[PROPS.options] && is_array($_GLOBAL.OBJ?.__QUERYS__?.[PROPS.options])) {
                            result = $_GLOBAL.OBJ?.__QUERYS__?.[PROPS.options];
                        }
                        else if ($_GLOBAL.OBJ?.[PROPS.options] && is_array($_GLOBAL.OBJ?.[PROPS.options])) {
                            result = $_GLOBAL.OBJ?.[PROPS.options];
                        }
                        else if (is_array(PROPS.options)) {
                            result = PROPS.options;
                        }
                    // ELSE
                // CREATE ARRAY





                // TERATMENT
                    // VERIFY FORM VALUE
                        if (checkbox){
                            for(const [key, value] of Object.entries($_GLOBAL.FORM.v[PROPS.name])){
                                const exists = result.some((item: any) => item.id == key);
                                if (!exists){
                                    delete $_GLOBAL.FORM.v[PROPS.name][key];
                                }
                            }
                        }
                    // VERIFY FORM VALUE
                // TERATMENT

                return result;
            }
        // OPTIONS__


        // VALUE__
            export function value__(PROPS: any): void
            {
                $_GLOBAL.FORM.v[PROPS.name] = ``;
                if (PROPS.type == `number`){
                    $_GLOBAL.FORM.v[PROPS.name] = 0;
                }

                // VALUE
                    if (PROPS?.value || PROPS?.value === 0 || PROPS?.value === '0'){
                        $_GLOBAL.FORM.v[PROPS.name] = PROPS.value;
                    }
                // VALUE

                // |->VALUE_EDIT
                    else if (compare__(`|->value_edit->`, PROPS?.extra) && $_GET['__EDIT__']){
                        let $extra = extra__(PROPS.extra, '|->value_edit');
                        $_GLOBAL.FORM.v[PROPS.name] = $extra?.[1] ? $extra?.[1] : ``;
                    }
                // |->VALUE_EDIT

                // |->VALUE
                    else if (COOKIES(`SEARCH__${PROPS.name}`)){
                        $_GLOBAL.FORM.v[PROPS.name] = COOKIES(`SEARCH__${PROPS.name}`);
                    }
                // |->VALUE

                // |->VALUE
                    else if (compare__(`|->value__->`, PROPS?.extra)){
                        if ($_GLOBAL.ROUTE?.params?.init__){
                            let $extra = extra__(PROPS.extra, '|->value__');
                            $_GLOBAL.FORM.v[PROPS.name] = $extra?.[1] ? $extra?.[1] : ``;
                        }
                    }
                // |->VALUE


                // TREATMENT
                    //
                    // MONTH
                        if (PROPS?.type == `month`){
                            let val = ``;
                            let ex = explode(`-`, $_GLOBAL.FORM.v[PROPS.name]);
                            if (ex?.[0] && ex?.[0]){
                                val = `${ex?.[0]}-${ex?.[1]}`;
                            }
                            $_GLOBAL.FORM.v[PROPS.name] = val;
                        }
                    // MONTH
                // TREATMENT
            }
        // VALUE__


        // VALUE_REL__
            export function value_rel__(value: any): boolean
            {
                if (compare__(`value_rel`, value?.extra)){
                    let extra = extra__(value?.extra, `|->value_rel`)

                    let ex = explode(`|`, extra?.[2]);
                    if (
                        (ex?.[0] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[0])
                        || ex?.[1] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[1]
                        || ex?.[2] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[2]
                        || ex?.[3] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[3]
                        || ex?.[4] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[4]
                        || ex?.[5] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[5]
                        || ex?.[6] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[6]
                        || ex?.[7] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[7]
                        || ex?.[8] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[8]
                        || ex?.[9] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[9]
                    ){
                        return true;        
                    }
                    return false;
                }
                return true;
            }
            export function value_not_rel__(value: any): boolean
            {
                if (compare__(`value_not_rel`, value?.extra)){
                    let extra = extra__(value?.extra, `|->value_not_rel`)

                    let ex = explode(`|`, extra?.[2]);
                    if (
                        (ex?.[0] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[0])
                        || ex?.[1] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[1]
                        || ex?.[2] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[2]
                        || ex?.[3] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[3]
                        || ex?.[4] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[4]
                        || ex?.[5] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[5]
                        || ex?.[6] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[6]
                        || ex?.[7] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[7]
                        || ex?.[8] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[8]
                        || ex?.[9] && $_GLOBAL.FORM.v?.[extra?.[1]] == ex?.[9]
                    ){
                        return false;        
                    }
                    return true;
                }
                return true;
            }
        // VALUE_REL__


        // EXTRA -> ex.: extra__(col?.extra, `|->value__`);
            export function extra__(extra: string, type: string): string[]
            {
                if (!extra || typeof extra !== 'string') {
                    return [];
                }
                
                const ex = extra.split(type);
                if (ex.length > 1 && ex[1]) {
                    let ex_1 = ex[1].split(' |->');

                    if (ex_1.length > 0 && ex_1[0]) {
                        let ex_2 = ex_1[0].split(' -->');

                        if (ex_2.length > 0 && ex_2[0]) {
                            return ex_2[0].split('->');
                        }
                    }
                }
                return [];
            }
        // EXTRA ->EX


        // FIRST_LETTER_UPPERCASE
            export function first_letter_uppercase(string: string): string
            {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        // FIRST_LETTER_UPPERCASE


        // HTTP
            export function http(value: string): string
            {
                if (!value){
                    return ``;
                }
				return (!/http/.test(value)) ? `http://${value}` : value;
            }
        // HTTP


        // OPEN__
            export function open__(pg: string, value: any = null, dashboard: number = 0, $window_location_href: boolean = false): void
            {
                COOKIES_CREATE(`HISTORY_BACK`, $_GET['ALL']);

                // OPEN
                    let queryParams = value !== null ? value : {};

                    // PG
                        // ADMIN
                            if ($_GET[0] == `admin`){
                                if (!compare__(`/admin`, pg)){
                                    pg = `/admin${pg}`;
                                }

                                // QUERY
                                    // N
                                        if (dashboard == 1){
                                            queryParams.n = $_GLOBAL.OBJ.menu_admin.table;
                                        }
                                    // N

                                    // T (MENU_ADMIN)
                                        if (pg.match(new RegExp(`\\b${$_GET[0]}\\w*`, 'gi'))){
                                            if ($_GET?.['t'] && value?.t == null){
                                                queryParams.t = $_GET['t'];
                                            }
                                            if (value?.t == `all`){
                                                value.t = null;
                                            }
                                        }
                                    // T (MENU_ADMIN)

                                // QUERY
                            }
                        // ADMIN

                        // DASHBOARD
                            if ($_GET[0] != `admin`){
                                if (!compare__(`/dashboard`, pg)){
                                    if (dashboard == 1){
                                        pg = `/dashboard${pg}`;

                                    } else if (dashboard == 2 && $_GET[0] == `dashboard`){
                                        pg = `/dashboard${pg}`;
                                    }
                                }
                            }
                        // DASHBOARD

                        pg = replace(`//`, `/`, pg);
                    // PG


                    // QUERY
                        // NEW
                            pg = NEW__open__pg(queryParams, pg, value, dashboard);
                            queryParams = NEW__open__queryParams(queryParams, pg, value, dashboard);
                        // NEW

                        queryParams.v = new Date().getTime();
                    // QUERY
                // OPEN





                // MOUSEEVENT
                    // EVENT
                        let event: MouseEvent | undefined;
                        if ($_GLOBAL.SHOW.lastMouseEvent){
                            event = $_GLOBAL.SHOW.lastMouseEvent;
                            $_GLOBAL.SHOW.lastMouseEvent = null;
                        } else {
                            event = window.event as MouseEvent;
                        }
                    // EVENT


                    // SHIFT PRESS COPY
                        if (event?.shiftKey) {
                            copy_table__(event);
                        }
                    // SHIFT PRESS COPY


                    // MOUSE CLICK RIGHT
                        else if (pg && event?.button === 2){
                            if ($_GLOBAL.SHOW.anchorMouseEvent){
                                let el = $_GLOBAL.SHOW.anchorMouseEvent;
                                let url = $_GLOBAL.ROUTER.resolve({ path: pg, query: queryParams }).href;

                                el.setAttribute('href', url);
                                $_GLOBAL.SHOW.anchorMouseEvent = null;
                            }
                        }
                    // MOUSE CLICK RIGHT


                    // MOUSE CLICK LEFT
                        else if (pg) {
                            if (event && (event?.ctrlKey || event?.metaKey)){
                                let url = $_GLOBAL.ROUTER.resolve({ path: pg, query: queryParams }).href;
                                open_blank(url);
                            } else if ($window_location_href){
                                window.location.href = pg;

                            } else {
                                $_GLOBAL.ROUTER.push({ path: pg, query: queryParams });
                            }
                            if (event) event.preventDefault();
                        }
                    // MOUSE CLICK LEFT
                // MOUSEEVENT


                // POS
                    $_GLOBAL.SHOW.MENU_SIDE_APP = 0;
                // POS
            }
        // OPEN__


        // OPEN_HREF
            export function open_href(url: string): void
            {
                window.location.href = url;
            }
        // OPEN_HREF


        // OPEN_BLANK
            export function open_blank(url: string, target = `_system`, options = `location=yes`): void // _self, _blank, _system
            {
                // @ts-ignore
                if (window.cordova && window.cordova.InAppBrowser) {
                    // @ts-ignore
                    window.cordova.InAppBrowser.open(url, target, options);
                } else {
                    window.open(url, `_blank`);
                }
            }
        // OPEN_BLANK


        // OPEN_FORM
            export function open_form(url: string, form: any, _blank: boolean = false): void
            {
                const formEl = document.createElement("form");
                formEl.method = "POST";
                formEl.action = url;
                formEl.target = _blank ? '_blank' : '';

                for (const key in form) {
                    if (!form.hasOwnProperty(key)) continue;
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = key;
                    input.value = form[key];
                    formEl.appendChild(input);
                }

                document.body.appendChild(formEl);
                formEl.submit();
                document.body.removeChild(formEl);
            }
        // OPEN_FORM


        // REFRESH
            export function refresh(params: any = null): void
            {
                $_GLOBAL.ROUTER.push({
                    path: $_GLOBAL.ROUTE.path,
                    query: {
                        ...$_GLOBAL.ROUTE.query,
                        ...params,
                        v: new Date().getTime(),
                    },
                });
            }
            export function open_refresh(): void
            {
                refresh();
            }
        // REFRESH


        // URL_ADMIN_DASHBOARD
            export function url_admin_dashboard(): string
            {
                let pg = ``;

                // ADMIN
                    if ($_GET[0] == `admin`){
                        pg = `${$_GET[1]}/${$_GET[2]}`;

                        if ($_GET[1] == `menu_admin`){
                            pg = `menu_admin`;
                        }
                    }
                // ADMIN


                // DASHBOARD
                    if ($_GET[0] == `dashboard`){
                        pg = `${$_GET[1]}`;
                    }
                // DASHBOARD


                // NEW
                    pg = NEW__url_admin_dashboard(pg);
                // NEW


                pg = replace(`//`, `/`, pg);

                return pg;
            }
        // URL_ADMIN_DASHBOARD


        // COPY__
            export function copy_table__(event: Event): void
            {
                const el = event.target as HTMLElement | null;
                if (el) {
                    let text = (el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement) ? el.value : el.innerText || el.textContent || '';

                    // CLIPBOARD API
                        const copy = async (t: string) => {
                            try {
                                if (navigator.clipboard?.writeText) {
                                    await navigator.clipboard.writeText(t);
                                } else {
                                    const ta = document.createElement('textarea');
                                    ta.value = t;
                                    ta.style.position = 'fixed';
                                    ta.style.opacity = '0';
                                    document.body.appendChild(ta);
                                    ta.select();
                                    document.execCommand('copy');
                                    document.body.removeChild(ta);
                                }
                            } catch (err) {
                            }
                        };
                    // CLIPBOARD API

                    if (text.trim()) {
                        copy(text);
                        alerts(1, 'Texto Copiado com Sucesso!', 0, 1, `top right`);
                    }
                }
            }
        // COPY__


        // COPY__
            export function copy__($class: string, $text = 'Link Copiado com Sucesso!'): void
            {
                const inputElement = document.querySelector($class) as HTMLInputElement;
                
                if (inputElement) {
                    inputElement.select();
                    inputElement.setSelectionRange(0, 99999); // Para garantir compatibilidade
                    
                    const successful = document.execCommand('copy');

                    inputElement.blur();
                    alerts(1, $text);

                } else {
                    console.error("Elemento não encontrado:", $class);
                }
            }
        // COPY__


        // DOWNLOAD
            export function download(file: string, name = `arquivo`): void
            {
                if (!file) {
                    console.error('Erro: O caminho do arquivo está vazio.');
                    return;
                }

                file = replace(`/`, `;;zz;;`, base64_encode(file));
                name = replace(`/`, `;;zz;;`, base64_encode(name));
                window.parent.location = `${DIR_API()}/download/${file}/${name}`;
            }
        // DOWNLOAD


        // NO_ACECENT
            export function no_accent($strToReplace: string): string
            {
                let $str_acento = "áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ";
                let $str_no_accent = "aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC";
                let result = "";
                for (let i = 0; i < $strToReplace.length; i++){
                    if ($str_acento.indexOf($strToReplace.charAt(i)) != -1){
                        result += $str_no_accent.substr($str_acento.search($strToReplace.substr(i, 1)), 1);
                    } else {
                        result += $strToReplace.substr(i, 1);
                    }
                }
                return result;
            }
        // NO_ACECENT


        // BASE64
            export function base64_encode($string: string): string
            {
                return btoa($string);
            }
            export function base64_decode($string: string): string
            {
                return atob($string);
            }
        // BASE64


        // MOBILE
            export function MOBILE($x: number = 1000): boolean
            {
                return $_GLOBAL.SHOW.width <= $x;
            }
        // MOBILE


        // CREATE_JS
            export function create_js(url: string): void
            {
                var script = document.createElement('script');
                script.src = url;
                script.type = 'text/javascript';
                script.onload = function() {
                };
                script.onerror = function() {
                    console.error('Erro ao carregar o script ' + url);
                };
                document.head.appendChild(script);
            }
        // CREATE_JS


        // CITY
            export function city__(FORM: any, OBJ: any, zerar_cidade = 1, $load = 0, $v = 'v'): void
            {
                if (FORM[$v].uf != null){
                    if ($_GLOBAL['city__'] == null){
                        $_GLOBAL['city__'] = 1;
                        setTimeout(() => { $_GLOBAL['city__'] = null }, 1000);

                        api(`/city`, { uf: FORM[$v].uf }, (request: any) => {
                            $_GLOBAL.OBJ.CITY = request.OBJ.city;
                            if (zerar_cidade){
                                FORM[$v].city = "";
                            }

                            if ($_GLOBAL['city__value'] != null && $_GLOBAL['city__value']){
                                FORM[$v].city = $_GLOBAL['city__value'];
                                $_GLOBAL['city__value'] = ``;
                            }

                            // SELECT2
                                select2_reset(`#select_uf`);
                                select2_reset(`#select_city`);
                            // SELECT2
                        }, $load)
                    }
                }
            }
        // CITY


        // ZIPCODE
            export function zipcode__(FORM: any, OBJ: any, $v = 'v'): void
            {
                if (FORM[$v].zipcode.length >= 10){
                    if ($_GLOBAL['zipcode__'] == null){
                        $_GLOBAL['zipcode__'] = 1;
                        setTimeout(() => { $_GLOBAL['zipcode__'] = null }, 2000);

                        // RESET
                            $_GLOBAL['address_ok'] = 0;
                            FORM[$v].address = ``;
                            FORM[$v].neighborhood = ``;
                            FORM[$v].uf = ``;
                            FORM[$v].city = ``;
                        // RESET

                        api(`/zipcode`, { zipcode: FORM[$v].zipcode }, (request: any) => {
                            FORM[$v].address = request.address;
                            FORM[$v].neighborhood = request.neighborhood;

                            // TXT
                                if (FORM[$v].uf_text != null){
                                    FORM[$v].uf_text = request.uf ? request.uf : ``;
                                    FORM[$v].city_text = request.city ? request.city : ``;
                                }
                            // TXT
                            
                            // SELECT
                                else {
                                    $_GLOBAL['city__'] = 1;
                                    FORM[$v].uf = request.uf ? request.uf : ``;
                                    $_GLOBAL['city__value'] = request.city ? request.city : ``;
                                    setTimeout(() => {
                                        $_GLOBAL['city__'] = null;
                                        city__(FORM, OBJ, 1, 0, $v);
                                    }, 50);

                                    if (request.city && request.uf){
                                        $_GLOBAL['address_ok'] = 1;
                                    }
                                }
                            // SELECT


                            // FOCUS
                                setTimeout(() => {
                                    if (document.activeElement?.getAttribute(`name`) != undefined){
                                        if (document.activeElement?.getAttribute(`name`)?.match(`zipcode`)){
                                            let inputs = document.querySelectorAll('input[type="text"]');
                                            let currentIndex = Array.prototype.indexOf.call(inputs, document.activeElement);
                                            if (currentIndex !== -1 && currentIndex < inputs.length - 1) {
                                                (inputs[currentIndex + 1] as HTMLElement).focus();
                                            }
                                        }
                                    }
                                }, 500);
                            // FOCUS

                        })    
                    }
                }
            }
        // ZIPCODE


        // GET_LAT_LNG
            export function get_lat_lng(address: string): Promise<string>
            {
                return new Promise((resolve, reject) => {
                    const apiKey = `AIzaSyCJ82_T7_ReAFQTzyX5E0D4PEhYH00skWA`;
                    const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'OK' && data.results.length > 0) {
                                const location = data.results[0].geometry.location;
                                const latLng = `${location.lat},${location.lng}`;
                                resolve(latLng);
                            } else {
                                console.error('Geocoding failed:', data.status);
                                resolve('');
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao buscar coordenadas:', error);
                            resolve('');
                        });
                });
            }
        // GET_LAT_LNG


        // FOCUS__
            export function focus__($class: string): void {
                setTimeout(() => {
                    const $input = document.querySelector($class) as HTMLInputElement;
                    if ($input){
                        $input.focus();
                    }
                }, 50);
            }
        // FOCUS__


        // SUBSTR
            /**
             * substr emula o comportamento da função substr do PHP em JavaScript.
             * @param str string de entrada
             * @param start posição inicial (pode ser negativa)
             * @param length quantidade de caracteres a retornar (opcional, pode ser negativa)
             * @returns substring conforme regras do PHP substr
             */
            export function substr(str: string, start: number, length?: number): string
            {
                if (str == null) return '';
                str = String(str);

                // Ajusta start negativo
                if (start < 0) {
                    start = str.length + start;
                    if (start < 0) start = 0;
                }

                // Se length não for definido, retorna até o final
                if (length === undefined) {
                    return str.substring(start);
                }

                // Se length for negativo, calcula o final relativo ao fim da string
                let end: number;
                if (length < 0) {
                    end = str.length + length;
                    if (end < 0) end = 0;
                } else {
                    end = start + length;
                }

                return str.substring(start, end);
            }
        // SUBSTR


        // AGE
            export function age(birthday: string): number
            {
                const today = new Date();
                const birthDate = new Date(birthday);
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }
        // AGE


        // OBSERVER
            export function OBSERVER(getCondition_1: () => any, condition_2: any, $function: Function | string | null = null, stop: boolean = true): any
            {
                let observerInterval: any = null;
                observerInterval = setInterval(() => {
                    const currentValue = typeof getCondition_1 === 'function' ? getCondition_1() : getCondition_1;
                    
                    if (currentValue === condition_2) {
                        if (typeof $function === 'function') {
                            $function();
                        }
                        
                        if (stop) {
                            clearInterval(observerInterval);
                        }
                    }
                }, 100);

                return observerInterval;
            }
        // OBSERVER


        // REDIRECIONAR PARA PRIMEIRO MENU
            export function REDIRECIONAR_PARA_PRIMEIRO_MENU(): void
            {
                if ($_GLOBAL.OBJ?.menu_side) {
                    for (const [key, value] of Object.entries($_GLOBAL.OBJ.menu_side)) {
                        let val = value as any;

                        // VERIFICAR MENU PRINCIPAL
                            if (val?.url && val.menus_type!=5 && !val?.logout) {
                                open__(val.url, { init__: 1 }, 1);
                                return;
                            }
                        // VERIFICAR MENU PRINCIPAL

                        // VERIFICAR SUBMENUS
                            if (val?.submenu) {
                                for (const [key_1, value_1] of Object.entries(val.submenu)) {
                                    let val_1 = value_1 as any;
                                    if (!val_1?.logout) {
                                        open__(val_1.url, { init__: 1 }, 1);
                                        return;
                                    }
                                }
                            }
                        // VERIFICAR SUBMENUS
                    }
                }
            }
        // REDIRECIONAR PARA PRIMEIRO MENU


        // HTML_TREATMENT
            export function html_treatment(html: string): string {
                if (!html || typeof html !== 'string') return '';
                
                // Remove scripts e tags perigosas
                return html
                    .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
                    .replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi, '')
                    .replace(/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/gi, '')
                    .replace(/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/gi, '')
                    .replace(/javascript:/gi, '')
                    .replace(/on\w+\s*=/gi, '')
                    .replace(/<link\b[^<]*>/gi, '')
                    .replace(/<meta\b[^<]*>/gi, '')
                    .replace(/vbscript:/gi, '')
                    .replace(/data:/gi, '')
                    .replace(/<form\b[^<]*(?:(?!<\/form>)<[^<]*)*<\/form>/gi, '')
                    .replace(/<input\b[^<]*>/gi, '')
                    .replace(/<textarea\b[^<]*(?:(?!<\/textarea>)<[^<]*)*<\/textarea>/gi, '')
                    .replace(/<select\b[^<]*(?:(?!<\/select>)<[^<]*)*<\/select>/gi, '')
                    .replace(/<button\b[^<]*(?:(?!<\/button>)<[^<]*)*<\/button>/gi, '');
            }
        // HTML_TREATMENT


        // LOGIN REMEMBER
            export function login_remember(type: string): void
            {
                setTimeout(() => {
                    let login = localStorage.getItem(`REMEMBER_${type}_${DIR()}`);
                    if(login){
                        $_GLOBAL.SHOW.REMEMBER = login;

                        let $observer = true;
                        if ($_GLOBAL.FORM.v?.remember && $_GLOBAL.SHOW.REMEMBER) {
                            let $login = base64_decode(base64_decode($_GLOBAL.SHOW.REMEMBER));
                            let [email, password] = $login.split(`--||--||--`);
                            $_GLOBAL.FORM.v.email = email;
                            $_GLOBAL.FORM.v.password = base64_decode(password);
                            $_GLOBAL.FORM.v.remember[1] = true;

                            $observer = false;
                        }

                        // OBSERVER
                            if($observer){
                                OBSERVER(() => $_GLOBAL.FORM.v.type, 'customers', () => {
                                    if ($_GLOBAL.FORM.v?.remember && $_GLOBAL.SHOW.REMEMBER) {
                                        let $login = base64_decode(base64_decode($_GLOBAL.SHOW.REMEMBER));
                                        let [email, password] = $login.split(`--||--||--`);
                                        $_GLOBAL.FORM.v.email = email;
                                        $_GLOBAL.FORM.v.password = base64_decode(password);
                                        $_GLOBAL.FORM.v.remember[1] = true;
                                    }
                                });
                            }
                        // OBSERVER
                    }
                }, .5);
            }
        // LOGIN REMEMBER


        // QRCODE_BARCODE
            export function qrcode_barcode(text: string): string
            {
                return replace(`/`, `____BARRA____`, text);
            }
        // QRCODE_BARCODE
    // USEFUL




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // CODIFICATION
        export function crip(value: string): string
        {
            const substituicoes = {
                '0': 'ENA;;QV',
                '1': 'BA-_VEC',
                '2': 'SD;;XAY',
                '3': 'TO||LUF',
                '4': 'LPD_,WU',
                '5': 'PFA-;YT',
                '6': 'GIV.;ZBX',
                '7': 'MED-;HRF',
                '8': 'RDB;$JY',
                '9': 'TCK#@XFG',
                '/': 'NIP;#TIU',
                ' ': 'YSU@;IR'
            };
            for (const [chave, valor] of Object.entries(substituicoes)) {
                value = value.split(chave).join(valor);
            }
            value = btoa(value);
            return value;
        }
        export function crip_(value: string): string
        {
            value = atob(value);
            const substituicoes = {
                'ENA;;QV': '0',
                'BA-_VEC': '1',
                'SD;;XAY': '2',
                'TO||LUF': '3',
                'LPD_,WU': '4',
                'PFA-;YT': '5',
                'GIV.;ZBX': '6',
                'MED-;HRF': '7',
                'RDB;$JY': '8',
                'TCK#@XFG': '9',
                'NIP;#TIU': '/',
                'YSU@;IR': ' '
            };
            for (const [chave, valor] of Object.entries(substituicoes)) {
                value = value.split(chave).join(valor);
            }
            return value;
        }
        export function cod(type: string, txt: string): string
        {
            let result = txt;
    
            if (type == `asc`){
                result = replace(`'`, `&#39;`, result);
                result = replace(`"`, `&#34;`, result);
                result = replace(`´`, `&#180;`, result);
                result = replace('`', `&#96;`, result);
                result = replace(`?`, `&#63;`, result);
                result = replace(`!`, `&#33;`, result);
                result = replace(`=`, `&#61;`, result);
                result = replace(`<`, `&#60;`, result);
                result = replace(`>`, `&#62;`, result);
            }

            return result;
        }
        export function not(type: string, text: string, replaceChar = '-'): string
        {
            // Helpers internos
            const stripTags = (str: string) => str.replace(/<\/?[^>]+(>|$)/g, "");
            const replaceArray = (search: string[], replace: string[], str: string) => {
                return search.reduce((acc, s, i) => {
                    const esc = s.replace(/[-/\\^$*+?.()|[\]{}]/g, '\\$&');
                    return acc.replace(new RegExp(esc, 'g'), replace[i] ?? '');
                }, str);
            };
            const htmlDecode = (input: string) => {
                const txt = document.createElement("textarea");
                txt.innerHTML = input;
                return txt.value;
            };
            const htmlEncode = (input: string) => {
                const txt = document.createElement("textarea");
                txt.textContent = input;
                return txt.innerHTML;
            };
        
            // Mapas de acentos
            const accentsMap: Record<string, string> = {
              "à":"a","á":"a","â":"a","ã":"a","ä":"a","å":"a","ç":"c",
              "è":"e","é":"e","ê":"e","ë":"e","ì":"i","í":"i","î":"i","ï":"i",
              "ñ":"n","ò":"o","ó":"o","ô":"o","õ":"o","ö":"o","ù":"u","ü":"u","ú":"u","ÿ":"y",
              "À":"A","Á":"A","Â":"A","Ã":"A","Ä":"A","Å":"A","Ç":"C",
              "È":"E","É":"E","Ê":"E","Ë":"E","Ì":"I","Í":"I","Î":"I","Ï":"I",
              "Ñ":"N","Ò":"O","Ó":"O","Ô":"O","Õ":"O","Ö":"O","Ù":"U","Ü":"U","Ú":"U","Ÿ":"Y"
            };
            const accentSearch = Object.keys(accentsMap);
            const accentReplace = Object.values(accentsMap);
        
            let result = text;
        
            switch (type) {
              case "tags":
                result = stripTags(text);
                break;
        
              case "url":
                result = replaceArray([" "], [replaceChar], result);
                result = replaceArray(accentSearch, accentReplace, result);
                const extraSym = ["°","º","ª","#","%","&","?","\\ ","\\\\","\"","'","/","´","`","~","^","!","@",":",";","*","<",">","(",")","|"," "];
                result = replaceArray(extraSym, Array(extraSym.length).fill(replaceChar), result);
                break;
        
              case "accents":
                result = replaceArray(accentSearch, accentReplace, text);
                break;
        
              case "accents_all":
                result = result
                  .replace(/\[.*?\]/g, "")
                  .replace(/&(amp;)?#?[a-z0-9]+;/gi, replaceChar);
                result = htmlEncode(result);
                result = result
                  .replace(/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/gi, "$1")
                  .replace(/[^a-z0-9]/gi, replaceChar)
                  .replace(/-+/g, replaceChar)
                  .toLowerCase()
                  .replace(new RegExp(`^${replaceChar}+|${replaceChar}+$`, "g"), "");
                break;
        
              case "symbols":
                result = replaceArray([" "].concat(accentSearch, ["\\ ","\\\\","\"","'","/"]),
                                      ["_"].concat(accentReplace, ["_","_","_","_"]), text);
                result = result.replace(/[^a-zA-Z0-9-\s]/g, replaceChar);
                break;
        
              case "html->utf8":
                result = htmlEncode(text);
                break;
        
              case "utf->html":
                result = htmlDecode(text);
                break;
        
              case "iso->utf8":
                try {
                  const decoder = new TextDecoder("iso-8859-1");
                  const encoder = new TextEncoder();
                  result = decoder.decode(encoder.encode(text));
                } catch {
                  result = text;
                }
                break;
        
              case "utf8->iso":
                try {
                  const encoder = new TextEncoder();
                  const decoder = new TextDecoder("utf-8");
                  result = decoder.decode(encoder.encode(text));
                } catch {
                  result = text;
                }
                break;
        
              case "s_end":
                const rules: [RegExp, string][] = [
                  [/ões$/i, "ão"],[/ães$/i, "ão"],[/ais$/i, "al"],[/éis$/i, "el"],
                  [/óis$/i, "ol"],[/is$/i, "il"],[/res$/i, "r"],[/zes$/i, "z"],
                  [/ses$/i, "s"],[/ns$/i, "m"],[/ãos$/i, "ão"],[/eis$/i, "el"],
                  [/ois$/i, "ol"],[/us$/i, "u"],[/s$/i, ""]
                ];
                for (const [re, rep] of rules) {
                  if (re.test(text)) {
                    result = text.replace(re, rep);
                    break;
                  }
                }
                break;
        
              default:
                result = text;
            }
        
            return result;
        }        
    // CODIFICATION




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // PLUGINS
        // __BOXS__
            export function __BOXS__($name: string): void
            {
                $_GLOBAL.SHOW.BOXS = $name;
                setTimeout(() => { 
                    const box = document.querySelector(`.__BOXS__ > div`) as HTMLElement;
                    if (box) {
                        const windowHeight = window.innerHeight;
                        const contentHeight = box.scrollHeight;
            
                        if (contentHeight > windowHeight || box.classList.contains(`top`) || box.classList.contains(`all`)){
                            box.style.alignItems = 'normal';
                            box.style.webkitBoxAlign = 'normal';
                        } else {
                            box.style.alignItems = 'center';
                            box.style.webkitBoxAlign = 'center';
                        }
                    }
                }, .5)
            }

            export function __BOXS_CLOSE__($name: string = ``): void{
                $_GLOBAL.SHOW.BOXS = ``;
            }

            export function __BOXS_INI__(): void
            {
                if ($_GET['boxs']){
                    __BOXS__($_GET['boxs']);

                    const url = new URL(window.location.href);
                    url.searchParams.delete('boxs');
                    window.history.replaceState(null, '', url.toString());

                } else {
                    __BOXS_CLOSE__();
                }
            }
        // __BOXS__




















        // ALERTS
            export function alerts_close(): void
            {
                document.querySelectorAll(`.__ALERTS__ .alerts`).forEach(item => {
                    item.innerHTML = ``;
                })

            }

            export function alerts(action: number = 0, txt: string = '', varios: number = 0, delay: number = 0, className: string = ''): void
            {
                if (!delay){
                    delay = 5;
                    if (action){
                        delay = 3;
                    }
                }

                if (!varios){
                    document.querySelectorAll('.__EVENTS__ .alerts .alert').forEach($item => ($item as HTMLElement).style.display = 'none');
                }

                if (!txt){
                    txt = action ? 'Operação Realizada com Sucesso!' : 'Ocorreu algum erro inesperado!';
                }

                const $rand = parseInt((Math.random() * 10000000).toString());
                let $html  = ``;
                $html += `<div class="c-p action_${action ? 1 : 0} alert alert_${$rand} ${className}"> `;
                    $html += `<div>${txt}</div> `;
                $html += `</div> `;

                const $e = document.querySelector('.__EVENTS__ .alerts');
                $e?.insertAdjacentHTML('beforeend', $html);
                animation(`.__EVENTS__ .alerts .alert_${$rand}`, 'fadeInOut', delay);

                document.querySelectorAll(`.__EVENTS__ .alert.alert_${$rand}`).forEach($item_1 => {
                    $item_1.addEventListener('click', () => {
                        animation(`.__EVENTS__ .alerts .alert_${$rand}`, 'fadeOut', delay);
                    });
                });
            }
        // ALERTS




















        // ANIMATION
            export function animation($classe: string, $animation: string, $delay = 5): void
            {
                let $e__ = is_e($classe);
                let $e: Element | null = null;
                if ($e__ instanceof Element) {
                    $e = $e__;
                } else if ($e__ instanceof NodeList && $e__.length > 0) {
                    $e = $e__[0] as Element;
                }
                if ($e){
                    $e.classList.add(`animation`);

                    // FADE
                        if ($animation == 'fadeIn'){
                            $e.classList.add(`fadeIn`);
                            ($e as HTMLElement).style.display = "block";
                            //setTimeout(function(){ $e.classList.remove(`fadeIn`); }, 2000);
                        }
                        if ($animation == 'fadeOut'){
                            $e.classList.add(`fadeOut`);
                            setTimeout(function(){
                                $e?.remove();
                            }, $delay*100);
                        }
                        if ($animation == 'fadeInOut'){
                            // In
                                $e.classList.add(`fadeIn`);
                                ($e as HTMLElement).style.display = "block";
                            // In
                            // Out
                                setTimeout(function(){
                                    $e?.classList.remove(`fadeIn`);
                                    animation($classe, `fadeOut`);
                                }, $delay*1000);
                            // Out
                        }
                    // FADE

                    // SLIDE
                        if ($animation == 'slideDown'){
                            if ($e){
                                $e.classList.add(`animation`);
                                $e.classList.add(`slideDown`);
                                ($e as HTMLElement).style.display = "block";
                                //setTimeout(function(){ $e.classList.remove(`slideDown`); }, 2000);
                            }
                        }
                        if ($animation == 'slideUp'){
                            if ($e){
                                $e.classList.add(`animation`);
                                $e.classList.add(`slideUp`);
                                ($e as HTMLElement).style.display = "block";
                                //setTimeout(function(){ $e.classList.remove(`slideUp`); }, 2000);
                            }
                        }
                    // SLIDE
                }

            }
        // ANIMATION




















        // CAROUSEL
            /*
            * https://ganlanyuan.github.io/tiny-slider/demo/
            */

            export function carousel(): void
            {

                document.querySelectorAll(`.__CAROUSEL__`).forEach($item => {
                    if (!$item.classList.contains(`__CAROUSEL__OK`)){
                        $item.classList.add(`__CAROUSEL__OK`);

                        // CARROCEL MOBILE
                            if ($item.classList.contains("__CAROUSEL_MOBILE__")) {
                                $item.classList.add(`dn_1000`);
                                $item.insertAdjacentHTML('afterend', `<div class="__CAROUSEL_MOBILE__ dnn_1000">${$item.innerHTML}</div>`);
                            }
                        // CARROCEL MOBILE

                        const $items = $item.getAttribute(`items`) ? $item.getAttribute(`items`) : 5;
                        const $banner = $item.getAttribute(`items`) == '1' ? true : false;
                        const $no_loop = $item.getAttribute(`no_loop`) == '1' ? false : true;
                        const $auto = $item.getAttribute(`auto`) == '0' ? false : true;
                        const $auto_time = $item.getAttribute(`auto`) == '0' ? false : parseInt($item.getAttribute(`auto`) || '0') * 1000;
                        const $flexible_height = $item.getAttribute(`flexible_height`) == '0' ? false : true;

                        const $obj: Record<string, any> = {
                            container: $item,
                            nav: true,
                            items: parseInt($items as string),
                            loop: $no_loop,
                            mouseDrag: true, // Arrastar o mouse
                            speed: 1000,
                            //lazyload: true, // carga preguicosa (se ativar desativa autoHeight)

                            autoplay: $auto,
                            autoplayTimeout: $auto_time,
                            //autoplayHoverPause: true,
                            //autoplayText: ["▶", "❚❚"],
                            //autoplayButtonOutput: false, // Botão de reprodução automática Saída

                            //fixedWidth: 400, // tamanho fixo
                            //autoWidth: true, // autoWidth
                            autoHeight: true, swipeAngle: false, // autoHeight

                            //startIndex: 6, // índice inicial
                            //arrowKeys: true, // mexer com o telhado
                            //controlsContainer: "#customize-controls", //personalizar controles

                            //mode: "gallery",
                            //animateIn: "jello",
                            //animateOut: "rollOut",

                            //navContainer: "#customize-thumbnails", // carrocel thumbs
                            //navAsThumbnails: true,
                            //autoplayTimeout 1000,
                            //autoplayButton: "#customize-toggle",

                        }

                        // PARA BANNER
                            if ($banner){
                                $obj.slideBy = `page`;
                            }
                        // PARA BANNER

                        // RESPONSIVE
                            const $responsive: Record<string, any> = {};

                            let $big = 1000;
                            //let $last = parseInt($items);
                            for (const $name of $item.getAttributeNames()) {
                                if ($name.startsWith(`resp_`)) {
                                    const $resp = $name.replace(`resp_`, ``);
                                    $responsive[$resp] = { items: parseInt($item.getAttribute($name) || "0") };
                                    $big = Math.max($big, parseInt($resp));
                                }
                            }
                            //$responsive[0] = { items: $last };

                            $responsive[$big + 1] = { items: parseInt($items as string) };

                            $obj.responsive = $responsive;
                        // RESPONSIVE


                        // CHAMANDO CARROCEL LIBRARY
                            let $class_init = $item.getAttribute(`class`);

                            let $tns_ = {};
                            try {
                                $tns_ = tns($obj);
                            } catch (e) { }

                            if ($item.classList.contains(`_TNS_`)){
                                $_GLOBAL.$TNS = $tns_;
                            }
                        // CHAMANDO CARROCEL LIBRARY

                        // ADD CLASS NO PARENT PARA CSS (SETA E PAGG)
                            const $classes = ($class_init || '')
                            .replace(`__CAROUSEL_MOBILE__`, ``)
                            .replace(`__CAROUSEL__OK`, ``)
                            .replace(`__CAROUSEL__`, ``)
                            .split(` `);

                            $classes.forEach(($value) => {
                                if ($value) {
                                    try {
                                        let $parentNode = $item.parentNode;
                                        while ($parentNode && $parentNode instanceof HTMLElement && !$parentNode.classList.contains(`tns-outer`)) {
                                            $parentNode = $parentNode.parentNode;
                                        }
                                        if ($parentNode && $parentNode instanceof HTMLElement) {
                                            $parentNode.classList.add($value);
                                        }
                                    } catch (e) {
                                        console.error(e);
                                    }
                                }
                            });
                        // ADD CLASS NO PARENT PARA CSS (SETA E PAGG)
                    }
                });


            }    
        // CAROUSEL




















        // EDITOR
            var $CKEDITOR: any[] = [];
            var $CKEDITOR_ID: any = 0;
            var $CKEDITOR_FOCUS: number = 0;
        
            const $editor_colors: {color: string, label: string}[] = [{color: `rgb(255, 235, 238)`, label: `Red 50`}, {color: `rgb(243, 229, 245)`, label: `Purple 50`}, {color: `rgb(232, 234, 246)`, label: `Indigo 50`}, {color: `rgb(227, 242, 253)`, label: `Blue 50`}, {color: `rgb(224, 247, 250)`, label: `Cyan 50`}, {color: `rgb(224, 242, 241)`, label: `Teal 50`}, {color: `rgb(241, 248, 233)`, label: `Light green 50`}, {color: `rgb(249, 251, 231)`, label: `Lime 50`}, {color: `rgb(255, 248, 225)`, label: `Amber 50`}, {color: `rgb(255, 243, 224)`, label: `Orange 50`}, {color: `rgb(250, 250, 250)`, label: `Grey 50`}, {color: `rgb(236, 239, 241)`, label: `Blue grey 50`}, {color: `rgb(255, 205, 210)`, label: `Red 100`}, {color: `rgb(225, 190, 231)`, label: `Purple 100`}, {color: `rgb(197, 202, 233)`, label: `Indigo 100`}, {color: `rgb(187, 222, 251)`, label: `Blue 100`}, {color: `rgb(178, 235, 242)`, label: `Cyan 100`}, {color: `rgb(178, 223, 219)`, label: `Teal 100`}, {color: `rgb(220, 237, 200)`, label: `Light green 100`}, {color: `rgb(240, 244, 195)`, label: `Lime 100`}, {color: `rgb(255, 236, 179)`, label: `Amber 100`}, {color: `rgb(255, 224, 178)`, label: `Orange 100`}, {color: `rgb(245, 245, 245)`, label: `Grey 100`}, {color: `rgb(207, 216, 220)`, label: `Blue grey 100`}, {color: `rgb(239, 154, 154)`, label: `Red 200`}, {color: `rgb(206, 147, 216)`, label: `Purple 200`}, {color: `rgb(159, 168, 218)`, label: `Indigo 200`}, {color: `rgb(144, 202, 249)`, label: `Blue 200`}, {color: `rgb(128, 222, 234)`, label: `Cyan 200`}, {color: `rgb(128, 203, 196)`, label: `Teal 200`}, {color: `rgb(197, 225, 165)`, label: `Light green 200`}, {color: `rgb(230, 238, 156)`, label: `Lime 200`}, {color: `rgb(255, 224, 130)`, label: `Amber 200`}, {color: `rgb(255, 204, 128)`, label: `Orange 200`}, {color: `rgb(238, 238, 238)`, label: `Grey 200`}, {color: `rgb(176, 190, 197)`, label: `Blue grey 200`}, {color: `rgb(229, 115, 115)`, label: `Red 300`}, {color: `rgb(186, 104, 200)`, label: `Purple 300`}, {color: `rgb(121, 134, 203)`, label: `Indigo 300`}, {color: `rgb(100, 181, 246)`, label: `Blue 300`}, {color: `rgb(77, 208, 225)`, label: `Cyan 300`}, {color: `rgb(77, 182, 172)`, label: `Teal 300`}, {color: `rgb(174, 213, 129)`, label: `Light green 300`}, {color: `rgb(220, 231, 117)`, label: `Lime 300`}, {color: `rgb(255, 213, 79)`, label: `Amber 300`}, {color: `rgb(255, 183, 77)`, label: `Orange 300`}, {color: `rgb(224, 224, 224)`, label: `Grey 300`}, {color: `rgb(144, 164, 174)`, label: `Blue grey 300`}, {color: `rgb(239, 83, 80)`, label: `Red 400`}, {color: `rgb(171, 71, 188)`, label: `Purple 400`}, {color: `rgb(92, 107, 192)`, label: `Indigo 400`}, {color: `rgb(66, 165, 245)`, label: `Blue 400`}, {color: `rgb(38, 198, 218)`, label: `Cyan 400`}, {color: `rgb(38, 166, 154)`, label: `Teal 400`}, {color: `rgb(156, 204, 101)`, label: `Light green 400`}, {color: `rgb(212, 225, 87)`, label: `Lime 400`}, {color: `rgb(255, 202, 40)`, label: `Amber 400`}, {color: `rgb(255, 167, 38)`, label: `Orange 400`}, {color: `rgb(189, 189, 189)`, label: `Grey 400`}, {color: `rgb(120, 144, 156)`, label: `Blue grey 400`}, {color: `rgb(244, 67, 54)`, label: `Red 500`}, {color: `rgb(156, 39, 176)`, label: `Purple 500`}, {color: `rgb(63, 81, 181)`, label: `Indigo 500`}, {color: `rgb(33, 150, 243)`, label: `Blue 500`}, {color: `rgb(0, 188, 212)`, label: `Cyan 500`}, {color: `rgb(0, 150, 136)`, label: `Teal 500`}, {color: `rgb(139, 195, 74)`, label: `Light green 500`}, {color: `rgb(205, 220, 57)`, label: `Lime 500`}, {color: `rgb(255, 193, 7)`, label: `Amber 500`}, {color: `rgb(255, 152, 0)`, label: `Orange 500`}, {color: `rgb(158, 158, 158)`, label: `Grey 500`}, {color: `rgb(96, 125, 139)`, label: `Blue grey 500`}, {color: `rgb(229, 57, 53)`, label: `Red 600`}, {color: `rgb(142, 36, 170)`, label: `Purple 600`}, {color: `rgb(57, 73, 171)`, label: `Indigo 600`}, {color: `rgb(30, 136, 229)`, label: `Blue 600`}, {color: `rgb(0, 172, 193)`, label: `Cyan 600`}, {color: `rgb(0, 137, 123)`, label: `Teal 600`}, {color: `rgb(124, 179, 66)`, label: `Light green 600`}, {color: `rgb(192, 202, 51)`, label: `Lime 600`}, {color: `rgb(255, 179, 0)`, label: `Amber 600`}, {color: `rgb(251, 140, 0)`, label: `Orange 600`}, {color: `rgb(117, 117, 117)`, label: `Grey 600`}, {color: `rgb(84, 110, 122)`, label: `Blue grey 600`}, {color: `rgb(211, 47, 47)`, label: `Red 700`}, {color: `rgb(123, 31, 162)`, label: `Purple 700`}, {color: `rgb(48, 63, 159)`, label: `Indigo 700`}, {color: `rgb(25, 118, 210)`, label: `Blue 700`}, {color: `rgb(0, 151, 167)`, label: `Cyan 700`}, {color: `rgb(0, 121, 107)`, label: `Teal 700`}, {color: `rgb(104, 159, 56)`, label: `Light green 700`}, {color: `rgb(175, 180, 43)`, label: `Lime 700`}, {color: `rgb(255, 160, 0)`, label: `Amber 700`}, {color: `rgb(245, 124, 0)`, label: `Orange 700`}, {color: `rgb(97, 97, 97)`, label: `Grey 700`}, {color: `rgb(69, 90, 100)`, label: `Blue grey 700`}, {color: `rgb(198, 40, 40)`, label: `Red 800`}, {color: `rgb(106, 27, 154)`, label: `Purple 800`}, {color: `rgb(40, 53, 147)`, label: `Indigo 800`}, {color: `rgb(21, 101, 192)`, label: `Blue 800`}, {color: `rgb(0, 131, 143)`, label: `Cyan 800`}, {color: `rgb(0, 105, 92)`, label: `Teal 800`}, {color: `rgb(85, 139, 47)`, label: `Light green 800`}, {color: `rgb(158, 157, 36)`, label: `Lime 800`}, {color: `rgb(255, 143, 0)`, label: `Amber 800`}, {color: `rgb(239, 108, 0)`, label: `Orange 800`}, {color: `rgb(66, 66, 66)`, label: `Grey 800`}, {color: `rgb(55, 71, 79)`, label: `Blue grey 800`}, {color: `rgb(183, 28, 28)`, label: `Red 900`}, {color: `rgb(74, 20, 140)`, label: `Purple 900`}, {color: `rgb(26, 35, 126)`, label: `Indigo 900`}, {color: `rgb(13, 71, 161)`, label: `Blue 900`}, {color: `rgb(0, 96, 100)`, label: `Cyan 900`}, {color: `rgb(0, 77, 64)`, label: `Teal 900`}, {color: `rgb(51, 105, 30)`, label: `Light green 900`}, {color: `rgb(130, 119, 23)`, label: `Lime 900`}, {color: `rgb(255, 111, 0)`, label: `Amber 900`}, {color: `rgb(230, 81, 0)`, label: `Orange 900`}, {color: `rgb(33, 33, 33)`, label: `Grey 900`}, { color: `rgb(38, 50, 56)`, label: `Blue grey 900` }];
            const $editor_font_family: string[] = [
                `default`,
                `Arial, Helvetica, sans-serif`,
                `Courier New, Courier, monospace`,
                `Cursive`,
                `Fantasy`,
                `Math`,
                `Monospace`,
                `System-ui`,
                'Ubuntu, Arial, sans-serif',
                'Ubuntu Mono, Courier New, Courier, monospace'
            ];

            // CRIAR
                export function editor_create(name: number): void
                {
                    let $editor = document.querySelector(`.editor__${name} .editor`) as HTMLElement | null;

                    if ($editor != null){
                        (window as any).DecoupledDocumentEditor.create($editor, {
                            toolbar: {
                                items: [
                                        'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor',
                                        {
                                            name: 'paragraph',
                                            label: 'Parágrafo',
                                            items: ['heading'],
                                        },
                                    '|',
                                        'bold',
                                        {
                                            name: 'style',
                                            label: 'Style',
                                            items: ['italic', 'strikethrough', 'Underline', 'blockquote', 'horizontalLine',],
                                        },
                                        'link',

                                    '|',
                                        'undo', 'redo',
                                    '|',
                                        {
                                            name: 'alignment',
                                            label: 'Alinhamento',
                                            items: ['alignment', 'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'],
                                        },
                                    '|',
                                        'uploadImage', 'mediaEmbed', 'insertTable',
                                    // '|',
                                    //     'sourceEditing'
                                ],
                                shouldNotGroupWhenFull: true
                            },
                            language: 'pt',
                            placeholder: '',
                            fontFamily: { options: $editor_font_family, supportAllValues: true },
                            fontSize: { options: [ 'default', 8, 10, 11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36, 38, 40, 42, 44, 46, 48, 50, 52, 54, 56, 58, 60, 62, 64, 66, 68, 70, 80 ] },
                            fontColor: { colors: $editor_colors, columns: 12, },
                            fontBackgroundColor: { colors: $editor_colors, columns: 12, },
                            table: { contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties'] },
                            heading: {
                                options: [
                                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' },
                                ]
                            },
                        })
                        .then((editor: any) => {
                            let toolbarContainer = document.querySelector(`.editor__${name} .toolbar-container`) as HTMLElement;
                            toolbarContainer.prepend(editor.ui.view.toolbar.element);
                            $CKEDITOR[name] = editor;
                            $CKEDITOR_ID = editor;

                            if ($_GLOBAL.FORM.v[name]){
                                $CKEDITOR[name].setData($_GLOBAL.FORM.v[name]);
                            }

                            editor.editing.view.document.on('change:isFocused', (evt: any, data: any, $isFocused: any) => {
                                if ($isFocused){
                                    $CKEDITOR_FOCUS = 1
                                } else {
                                    setTimeout(function(){ $CKEDITOR_FOCUS = 0; }, 500);
                                }
                            });

                            editor.editing.view.document.on('layoutChanged', (event: any) => {
                                $_GLOBAL.FORM.v[name] = editor.getData();
                            });
                                
                            editor.plugins.get('FileRepository').createUploadAdapter = (loader: any) => {
                                return new Editor__Upload(loader);
                            };
                        })
                        .catch((err: any) => {
                            console.error(err.stack);
                        });
                    }
                }
            // CRIAR


            // UPLOAD
                class Editor__Upload {
                    loader: any;
                    url: string;
                    xhr: XMLHttpRequest | null = null;

                    constructor(loader: any) {
                        load();
                        this.loader = loader;
                        this.url = `${DIR_API()}/${$_GET[0]}/ckeditor/upload`;
                    }
                    upload(): Promise<string> {
                        return this.loader.file
                            .then( (file: File) => new Promise<string>((resolve, reject) => {
                                this._initRequest();
                                this._initListeners(resolve, reject, file);
                                this._sendRequest(file);
                                load_close();
                            })
                        );
                    }
                    abort(): void {
                        if (this.xhr){
                            this.xhr.abort();
                            load_close();
                        }
                    }

                    // ACAO
                        _initRequest(): void {
                            const xhr = this.xhr = new XMLHttpRequest();
                            xhr.open( 'POST', this.url, true );
                            xhr.responseType = 'json';

                            xhr.withCredentials = true;

                            const token = rootAuth();
                            xhr.setRequestHeader('Authorization', `Bearer ${token}`);

                        }
                        private _initListeners(resolve: (value: string | PromiseLike<string>) => void, reject: (reason?: any) => void, file: File): void {
                            const xhr = this.xhr!;
                            const loader = this.loader;
                            const genericErrorText = `Não foi Possível Salvar o Arquivo: ${ file.name }.`;

                            xhr.addEventListener('error', () => reject(genericErrorText));
                            xhr.addEventListener('abort', () => reject());
                            xhr.addEventListener('load', () => {
                                const response = xhr.response;

                                if (response.erro != null){
                                    if (response.erro[0] != null){
                                        return reject(response.erro[0]);
                                    }
                                }
                                resolve(response.url);
                            });

                            if (xhr.upload){
                                xhr.upload.addEventListener('progress', evt => {
                                    if (evt.lengthComputable){
                                        loader.uploadTotal = evt.total;
                                        loader.uploaded = evt.loaded;
                                    }
                                });
                            }
                        }
                        _sendRequest(file: File): void {
                            const data = new FormData();
                            data.append('file', file);
                            this.xhr!.send(data);
                        }
                    // ACAO
                }
            // UPLOAD


            // INSERIR
                export function editor_insert($html: string): void
                {
                    $CKEDITOR_ID.model.change((writer: any) => {
                        writer.insertText($html, $CKEDITOR_ID.model.document.selection.getFirstPosition());
                    });
                    
                }
            // INSERIR
        // EDITOR




















        // DRAG AND DROP
            export function drag_drop__(): void
            {
                // Verificar se já foi configurado para evitar duplicação
                if ((window as any).__drag_drop_configured) {
                    return;
                }
                (window as any).__drag_drop_configured = true;

                // Configurar drop zones para editores
                const editors = document.querySelectorAll('.editor__ .editor');
                editors.forEach(editor => {
                    // Verificar se já tem listeners para evitar duplicação
                    if ((editor as any).__drag_drop_configured) {
                        return;
                    }
                    (editor as any).__drag_drop_configured = true;

                    editor.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        (e as DragEvent).dataTransfer!.dropEffect = 'copy';
                        editor.classList.add('dragover');
                    });

                    editor.addEventListener('dragleave', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        editor.classList.remove('dragover');
                    });

                    editor.addEventListener('drop', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        editor.classList.remove('dragover');
                        
                        const variable = (e as DragEvent).dataTransfer?.getData('text/plain');
                        if (variable) {
                            // Insere diretamente no editor
                            (editor as HTMLElement).focus();
                            const selection = window.getSelection();
                            if (selection) {
                                const range = selection.getRangeAt(0);
                                range.deleteContents();
                                range.insertNode(document.createTextNode(variable));
                            }
                        }
                    });
                });

                // Configurar drop zones para inputs - VERSÃO CORRIGIDA
                const inputs = document.querySelectorAll('input[type="text"], input[type="email"], textarea');
                inputs.forEach(input => {
                    // Verificar se já tem listeners para evitar duplicação
                    if ((input as any).__drag_drop_configured) {
                        return;
                    }
                    (input as any).__drag_drop_configured = true;

                    // Função para calcular posição do cursor baseada na posição do mouse
                    function getCaretPositionFromPoint(element: HTMLInputElement | HTMLTextAreaElement, clientX: number, clientY: number): number {
                        const rect = element.getBoundingClientRect();
                        const x = clientX - rect.left;
                        const y = clientY - rect.top;
                        
                        // Verificar se o mouse está dentro do elemento
                        if (x < 0 || y < 0 || x > rect.width || y > rect.height) {
                            return element.selectionStart || 0;
                        }
                        
                        // MÉTODO 1: Tentar usar caretRangeFromPoint (mais preciso)
                        if ((document as any).caretRangeFromPoint) {
                            try {
                                const range = (document as any).caretRangeFromPoint(clientX, clientY);
                                if (range && range.startContainer && element.contains(range.startContainer)) {
                                    return range.startOffset;
                                }
                            } catch (e) {
                                // Continuar para próximo método
                            }
                        }
                        
                        // MÉTODO 2: Calcular posição manualmente para textarea
                        if (element.tagName === 'TEXTAREA') {
                            return getTextareaCaretPosition(element as HTMLTextAreaElement, x, y);
                        }
                        
                        // MÉTODO 3: Para inputs simples, usar proporção horizontal
                        const style = window.getComputedStyle(element);
                        const paddingLeft = parseFloat(style.paddingLeft) || 0;
                        const paddingRight = parseFloat(style.paddingRight) || 0;
                        const availableWidth = element.clientWidth - paddingLeft - paddingRight;
                        
                        if (availableWidth > 0) {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');
                            if (ctx) {
                                ctx.font = `${style.fontSize} ${style.fontFamily}`;
                                const totalTextWidth = ctx.measureText(element.value).width;
                                
                                if (totalTextWidth > 0) {
                                    const clickRatio = Math.max(0, Math.min(1, (x - paddingLeft) / Math.min(availableWidth, totalTextWidth)));
                                    return Math.floor(clickRatio * element.value.length);
                                }
                            }
                        }
                        
                        return element.selectionStart || 0;
                    }
                    
                    // Função específica para calcular posição em textarea
                    function getTextareaCaretPosition(textarea: HTMLTextAreaElement, x: number, y: number): number {
                        const style = window.getComputedStyle(textarea);
                        const fontSize = parseFloat(style.fontSize);
                        const lineHeight = parseFloat(style.lineHeight) || fontSize * 1.2;
                        const paddingTop = parseFloat(style.paddingTop) || 0;
                        const paddingLeft = parseFloat(style.paddingLeft) || 0;
                        const paddingRight = parseFloat(style.paddingRight) || 0;
                        
                        
                        // Calcular linha baseada na posição Y
                        const adjustedY = Math.max(0, y - paddingTop);
                        const targetLineNumber = Math.floor(adjustedY / lineHeight);
                        
                        
                        // Largura disponível para o texto (considerando word-wrap)
                        const availableWidth = textarea.clientWidth - paddingLeft - paddingRight;
                        
                        // Usar canvas para medição precisa
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        if (!ctx) {
                            return textarea.selectionStart || 0;
                        }
                        
                        ctx.font = `${fontSize}px ${style.fontFamily}`;
                        
                        // NOVA ABORDAGEM: Simular quebras automáticas
                        const text = textarea.value;
                        const words = text.split(' ');
                        let currentLine = 0;
                        let currentLineWidth = 0;
                        let currentPosition = 0;
                        let lineStartPositions = [0]; // Posições onde cada linha visual começa
                        
                        
                        // Simular quebras automáticas palavra por palavra
                        for (let i = 0; i < words.length; i++) {
                            const word = words[i];
                            const wordWidth = ctx.measureText(word + ' ').width;
                            
                            // Se a palavra não cabe na linha atual, quebra
                            if (currentLineWidth + wordWidth > availableWidth && currentLineWidth > 0) {
                                currentLine++;
                                currentLineWidth = wordWidth;
                                lineStartPositions.push(currentPosition);
                            } else {
                                currentLineWidth += wordWidth;
                            }
                            
                            currentPosition += word.length;
                            if (i < words.length - 1) currentPosition += 1; // espaço
                        }
                        
                        
                        // Se a linha clicada não existe, usar a última linha
                        const actualTargetLine = Math.min(targetLineNumber, lineStartPositions.length - 1);
                        
                        // Posição onde a linha clicada começa
                        const lineStartPos = lineStartPositions[actualTargetLine];
                        
                        // Posição onde a linha clicada termina
                        const lineEndPos = actualTargetLine < lineStartPositions.length - 1 
                            ? lineStartPositions[actualTargetLine + 1] - 1 
                            : text.length;
                        
                        // Texto da linha clicada
                        const lineText = text.substring(lineStartPos, lineEndPos);
                        
                        // Calcular posição horizontal dentro da linha
                        const targetX = Math.max(0, x - paddingLeft);
                        let currentWidth = 0;
                        
                        for (let i = 0; i <= lineText.length; i++) {
                            if (i === lineText.length) {
                                return lineStartPos + i;
                            }
                            
                            const char = lineText[i];
                            const charWidth = ctx.measureText(char).width;
                            
                            // Se o clique está na primeira metade do caractere, inserir antes
                            if (currentWidth + charWidth / 2 > targetX) {
                                return lineStartPos + i;
                            }
                            
                            currentWidth += charWidth;
                        }
                        
                        // Fallback: fim da linha
                        const fallbackPos = lineStartPos + lineText.length;
                        return fallbackPos;
                    }

                    // Armazenar a posição do cursor quando o usuário clica ou move o mouse
                    let lastClickPosition = 0;
                    
                    input.addEventListener('click', (e) => {
                        const inputElement = e.target as HTMLInputElement | HTMLTextAreaElement;
                        lastClickPosition = inputElement.selectionStart || 0;
                    });
                    
                    input.addEventListener('mouseup', (e) => {
                        const inputElement = e.target as HTMLInputElement | HTMLTextAreaElement;
                        lastClickPosition = inputElement.selectionStart || 0;
                    });

                    input.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        (e as DragEvent).dataTransfer!.dropEffect = 'copy';
                        input.classList.add('dragover');
                    });

                    input.addEventListener('dragleave', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        input.classList.remove('dragover');
                    });

                    input.addEventListener('drop', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        input.classList.remove('dragover');
                        
                        const variable = (e as DragEvent).dataTransfer?.getData('text/plain');
                        if (variable) {
                            const inputElement = e.target as HTMLInputElement | HTMLTextAreaElement;
                            const dropEvent = e as DragEvent;
                            
                            // Foca no elemento primeiro para garantir que a seleção seja válida
                            inputElement.focus();
                            
                            // Aguarda um momento para o foco ser estabelecido
                            setTimeout(() => {
                                // CORREÇÃO: Calcular posição baseada na posição do mouse no drop
                                let insertPosition = getCaretPositionFromPoint(inputElement, dropEvent.clientX, dropEvent.clientY);
                                
                                // Verificar se há uma seleção ativa
                                let start = insertPosition;
                                let end = insertPosition;
                                
                                if (inputElement.selectionStart !== null && inputElement.selectionEnd !== null) {
                                    if (inputElement.selectionStart !== inputElement.selectionEnd) {
                                        // Há uma seleção ativa, usar ela
                                        start = inputElement.selectionStart;
                                        end = inputElement.selectionEnd;
                                    } else {
                                        // Não há seleção, usar posição calculada do mouse
                                        start = insertPosition;
                                        end = insertPosition;
                                    }
                                } else {
                                    // Fallback para a posição calculada
                                    start = insertPosition;
                                    end = insertPosition;
                                }
                                
                                const value = inputElement.value;
                                
                                // Insere a variável na posição calculada
                                const newValue = value.substring(0, start) + variable + value.substring(end);
                                inputElement.value = newValue;
                                
                                // Atualiza a posição do cursor
                                const newCursorPos = start + variable.length;
                                inputElement.setSelectionRange(newCursorPos, newCursorPos);
                                
                                // Dispara evento de input para atualizar o v-model
                                inputElement.dispatchEvent(new Event('input', { bubbles: true }));
                            }, 10);
                        }
                    });
                });
            }
        // DRAG AND DROP










        // MASK
            export function f_mask__(mask: string, value: any): string
            {
                let result = ``;

                // TYPE
                    if (value.type == `tel`){
                        result = `phone`;
                    }
                // TYPE

                // NAME
                    if (value.name == `cpf`){
                        result = `cpf`;
                    }
                    else if (value.name == `cnpj`){
                        result = `cnpj`;
                    }
                    else if (value.name == `phone`){
                        result = `phone`;
                    }
                    else if (value.name == `cpf_cnpj`){
                        result = `cpf_cnpj`;
                    }
                    else if (value.name == `zipcode`){
                        result = `zipcode`;
                    }
                // NAME

                if (mask && mask != undefined){
                    result = mask;
                }

                return result;
            }

            export function mask__(name: string, field: string): void
            {
                setTimeout(() => { 
                    if ($masks[field] != null){
                        $_GLOBAL.FORM.v[name] = $masks[field]($_GLOBAL.FORM.v[name]);
                    }
                }, 50);
            }

            const $masks: { [key: string]: (value: string) => string } = {
                // DATE
                    date(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 8);
                    
                        if (value.length <= 2) {
                            return value;
                        } else if (value.length <= 4) {
                            return `${value.substring(0, 2)}/${value.substring(2)}`;
                        } else {
                            return `${value.substring(0, 2)}/${value.substring(2, 4)}/${value.substring(4)}`;
                        }
                    },                
                // DATE

                // DATE HOUR_MIN
                    date_time(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 12);
                    
                        const len = value.length;
                        if (len <= 2) {
                            return value;
                        } else if (len <= 4) {
                            return `${value.substring(0, 2)}/${value.substring(2)}`;
                        } else if (len <= 8) {
                            return `${value.substring(0, 2)}/${value.substring(2, 4)}/${value.substring(4)}`;
                        } else if (len <= 10) {
                            return `${value.substring(0, 2)}/${value.substring(2, 4)}/${value.substring(4, 8)} ${value.substring(8)}`;
                        } else {
                            return `${value.substring(0, 2)}/${value.substring(2, 4)}/${value.substring(4, 8)} ${value.substring(8, 10)}:${value.substring(10, 12)}`;
                        }
                    },                
                // DATE HOUR_MIN

                // HOUR_MIN
                    hour_min(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 4);
                    
                        if (value.length <= 2) {
                            return value;
                        } else {
                            return `${value.substring(0, 2)}:${value.substring(2)}`;
                        }
                    },              
                // HOUR_MIN

                // CPF
                    cpf(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 11);

                        if (value.length <= 3) {
                            return value;
                        } else if (value.length <= 6) {
                            return `${value.substring(0, 3)}.${value.substring(3)}`;
                        } else if (value.length <= 9) {
                            return `${value.substring(0, 3)}.${value.substring(3, 6)}.${value.substring(6)}`;
                        } else {
                            return `${value.substring(0, 3)}.${value.substring(3, 6)}.${value.substring(6, 9)}-${value.substring(9)}`;
                        }
                    },
                // CPF

                // CNPJ
                    cnpj(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 14);
                    
                        if (value.length <= 2) {
                            return value;
                        } else if (value.length <= 5) {
                            return `${value.substring(0, 2)}.${value.substring(2)}`;
                        } else if (value.length <= 8) {
                            return `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5)}`;
                        } else if (value.length <= 12) {
                            return `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5, 8)}/${value.substring(8)}`;
                        } else {
                            return `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5, 8)}/${value.substring(8, 12)}-${value.substring(12)}`;
                        }
                    },
                // CNPJ

                // CPF / CNPJ
                    cpf_cnpj(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                    
                        // CPF: ###.###.###-##
                            if (value.length <= 11) {
                                value = value.substring(0, 11);
                                if (value.length <= 3) {
                                    return value;
                                } else if (value.length <= 6) {
                                    return `${value.substring(0, 3)}.${value.substring(3)}`;
                                } else if (value.length <= 9) {
                                    return `${value.substring(0, 3)}.${value.substring(3, 6)}.${value.substring(6)}`;
                                } else {
                                    return `${value.substring(0, 3)}.${value.substring(3, 6)}.${value.substring(6, 9)}-${value.substring(9)}`;
                                }
                            }
                        // CPF: ###.###.###-##
                    
                        // CNPJ: ##.###.###/####-##
                            value = value.substring(0, 14);
                            if (value.length <= 2) {
                                return value;
                            } else if (value.length <= 5) {
                                return `${value.substring(0, 2)}.${value.substring(2)}`;
                            } else if (value.length <= 8) {
                                return `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5)}`;
                            } else if (value.length <= 12) {
                                return `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5, 8)}/${value.substring(8)}`;
                            } else {
                                return `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5, 8)}/${value.substring(8, 12)}-${value.substring(12)}`;
                            }
                        // CNPJ: ##.###.###/####-##
                    },
                // CPF / CNPJ

                // RG
                    rg(value) {
                        if (!value) return '';
                        value = value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                        value = value.substring(0, 10);
                    
                        const len = value.length;
                        if (len <= 2) {
                            return value;
                        } else if (len <= 5) {
                            return `${value.substring(0, 2)}.${value.substring(2)}`;
                        } else if (len <= 8) {
                            return `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5)}`;
                        } else {
                            return `${value.substring(0, 2)}.${value.substring(2, 5)}.${value.substring(5, 8)}-${value.substring(8)}`;
                        }
                    },                
                // RG

                // PHONE
                    phone(value){
                        if (!value) return '';
                        value = value.replace(/\D/g, '');

                        // USA
                            if($_GLOBAL.OBJ?.info?.phone_format == '+1'){
                                value = value.substring(0, 10);

                                if (value.length <= 3) {
                                    return `(${value}`;
                                } else if (value.length <= 6) {
                                    return `(${value.substring(0, 3)}) ${value.substring(3)}`;
                                } else {
                                    return `(${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6)}`;
                                }
                            }
                        // USA

                        // BRAZIL
                            value = value.substring(0, 11);

                            if (value.length <= 2) {
                                return `(${value}`;
                            } else if (value.length <= 6) {
                                return `(${value.substring(0, 2)}) ${value.substring(2)}`;
                            } else if (value.length <= 10) {
                                return `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
                            } else {
                                return `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7, 11)}`;
                            }
                        // BRAZIL
                    },
                // PHONE

                // ZIPCODE
                    zipcode(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 8);
                    
                        if (value.length <= 2) {
                            return value;
                        } else if (value.length <= 5) {
                            return `${value.substring(0, 2)}.${value.substring(2)}`;
                        } else {
                            return `${value.substring(0, 2)}.${value.substring(2, 5)}-${value.substring(5)}`;
                        }
                    },                    
                // ZIPCODE

                // BANK_AGENCY
                    bank_agency(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 5);
                    
                        if (value.length <= 3) {
                            return value;
                        } else if (value.length === 4) {
                            return `${value.substring(0, 3)}-${value.substring(3)}`;
                        } else {
                            return `${value.substring(0, 3)}-${value.substring(3, 5)}`;
                        }
                    },                    
                // BANK_AGENCY

                // BANK_ACCOUNT
                    bank_account(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 7);
                    
                        const len = value.length;
                        if (len <= 3) {
                            return value;
                        } else {
                            return `${value.substring(0, len - 1)}-${value.substring(len - 1)}`;
                        }
                    },                    
                // BANK_ACCOUNT

                // CARD_NUMBER
                    card_number(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 16);
                    
                        return value.replace(/(.{4})/g, '$1 ').trim();
                    },                    
                // CARD_NUMBER

                // CARD_VALIDATE
                    card_validate(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        value = value.substring(0, 6);
                    
                        if (value.length <= 2) {
                            return value;
                        } else if (value.length <= 4) {
                            return `${value.substring(0, 2)}/${value.substring(2)}`;
                        } else {
                            return `${value.substring(0, 2)}/${value.substring(2, 6)}`;
                        }
                    },                    
                // CARD_VALIDATE

                // CARD_CVV
                    card_cvv(value) {
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        return value.substring(0, 4);
                    },                    
                // CARD_CVV

                // PLACA
                    placa(value) {
                        if (!value) return '';
                        value = value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                        value = value.substring(0, 7);
                    
                        if (value.length <= 3) {
                            return value;
                        } else {
                            return `${value.substring(0, 3)}-${value.substring(3)}`;
                        }
                    },
                // PLACA

                // PRICE
                    price(value){ // R$ 1.000,00
                        if (!value) return '';
                        const cleanValue = +value.replace(/\D+/g, '')

                        if ($_GLOBAL.OBJ?.info?.currency == '$'){
                            const options: Intl.NumberFormatOptions = {
                                style: 'currency',
                                currency: 'USD'
                            }
                            return new Intl.NumberFormat('en-us', options).format(cleanValue / 100)
        
                        } else {
                            const options: Intl.NumberFormatOptions = {
                                style: 'currency',
                                currency: 'BRL'
                            }
                            return new Intl.NumberFormat('pt-br', options).format(cleanValue / 100)
                        }
        
                    },
                // PRICE

                // PRICE_1
                    price_1(value){ // 1.000,00
                        if (!value) return '';
                        const cleanValue = +String(value).replace(/\D+/g, '')
                        const options: Intl.NumberFormatOptions = {
                            style: 'decimal',
                        }
                        return new Intl.NumberFormat('pt-br', options).format(cleanValue / 100)
                    },
                // PRICE_1

                // PRICE_EXTRA
                    price_extra(value) { // R$ 1.000,00
                        if (!value) return '';
                        value = value.replace(/\D/g, '');
                        const numericValue = (parseInt(value, 10) / 100).toFixed(2);
                    
                        return $_GLOBAL.OBJ?.info?.currency ? $_GLOBAL.OBJ?.info?.currency : `R$` + ' ' + numericValue
                            .replace('.', ',')
                            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    },
                // PRICE_EXTRA

                // PERCENT
                    perc(value){
                        if (!value) return '';
                        const cleanValue = +String(value).replace(/\D+/g, '')
                        const options: Intl.NumberFormatOptions = {
                            style: 'decimal',
                            minimumFractionDigits:2,
                            maximumFractionDigits:2
                        }
                        let result = new Intl.NumberFormat('ja-JP', options).format(cleanValue / 100)

                        result = replace(`,`, ``, result);
                        return result
                    },
                    percent(value){
                        if (!value) return '';
                        const cleanValue = +String(value).replace(/\D+/g, '')
                        const options: Intl.NumberFormatOptions = {
                            style: 'decimal',
                            minimumFractionDigits:2,
                            maximumFractionDigits:2
                        }
                        let result = new Intl.NumberFormat('ja-JP', options).format(cleanValue / 100)

                        result = replace(`,`, ``, result);
                        return result
                    },
                // PERCENT

                // PERC_EXTRA
                    perc_extra(value) {
                        if (!value) return '';
                    
                        // Remove tudo que não for número
                        value = value.replace(/\D/g, '');
                    
                        // Limita a 2 dígitos
                        value = value.substring(0, 2);
                    
                        return value ? `${value}%` : '';
                    },
                // PERC_EXTRA


                // INSTALLMENTS -> ADD -> required mask="installments" pattern="\d{3}/\d{2,3}" title="Use o formato 001/001, 001/010 ou 002/010"
                    installments(value: string) {
                        if (!value) return '';

                        value = value.replace(/[^0-9/]/g, '');
                        if (value.includes('/')) {
                            let [raw1, raw2 = ''] = value.split('/');

                            let part1 = raw1.padStart(3, '0').slice(-3);
                            let part2 = raw2.padStart(3, '0').slice(-3);

                            if (part1 === '000') part1 = '001';
                            if (part2 === '000') part2 = raw2 === '' ? '' : '001';  
                            
                            if (raw2 === '') return `${part1}/`;

                            return `${part1}/${part2}`;
                        }
                        
                        let solo = value.padStart(3, '0').slice(-3);
                        if (solo === '000') solo = '001';      
                        return solo;
                    }
                // INSTALLMENTS
            }
        // MASK




















    // SELECT2
        var $KEYBOARD_EVENTS = 0;

        // SELECT2
            export function select2_reset($class: string): void
            {
                let select = document.querySelectorAll(`select${$class}`);
                select.forEach(item => {
                    if (item.classList.contains('select2-ok')){
                        item.classList.remove('select2-ok');
                        item.parentNode?.querySelectorAll('.select2').forEach(item => { item.remove() });
                    }
                    setTimeout(() => {
                        // let event = document.createEvent("HTMLEvents");
                        // event.initEvent("change", true, false);
                        // item.dispatchEvent(event);
                        select2();
                    }, 50);
                })
            }

            export function select2(force: boolean = false)
            {
                let $rand = rand();
                $_GLOBAL.SHOW.select2__next = $rand;

                setTimeout(() => {
                    if($_GLOBAL.SHOW.select2__next == $rand || force){ // nao rodar se tiver o outro select2 vindo de outro componente
                        // DESK
                            if (!MOBILE()){
                                let $select2s = document.querySelectorAll('select.design');
                                if ($select2s.length > 0){
                                    $select2s.forEach($item => {
                                        if (!$item.classList.contains('select2-ok')){
                                            $item.classList.add('select2-ok');

                                            const parentElement = $item.parentNode as HTMLElement | null;
                                            if (parentElement) {
                                                parentElement.classList.add('posr');
                                            }

                                            select2_own($item);
                                        }
                                    });
                                }
                            }
                        // DESK

                        // MOBILE
                            else {
                                let $select2s = document.querySelectorAll('select.design');
                                if ($select2s.length > 0){
                                    $select2s.forEach($item => {
                                        if (!$item.classList.contains('select2-ok')){
                                            $item.classList.add('select2-ok');
                                            $item.classList.add('designx');
                                        }
                                    });
                                }
                            }
                        // MOBILE

                        // KEYBOARD EVENTS
                            if (!$KEYBOARD_EVENTS){
                                $KEYBOARD_EVENTS = 1;
                                document.addEventListener('keydown', function(e: KeyboardEvent){ // https://www.freecodecamp.org/portuguese/news/lista-de-codigos-de-tecla-em-javascript-codigos-de-tecla-de-evento-de-pressionamento-de-tecla-para-enter-barra-de-espaco-backspace-e-outros/
                                    if (e.keyCode == 13){ // ENTER
                                        select2_keyboard_enter(e);
                                        select_multiple_keyboard_enter(e);

                                    } else if (e.keyCode == 38){ // CIMA
                                        select2_keyboard_up_down('cima');

                                    } else if (e.keyCode == 40){ // BAIXO
                                        select2_keyboard_up_down('baixo');

                                    } else if (e.keyCode == 9){ // TABS
                                        // FECHAR SELECTS ABERTOS
                                        const openedSelectedElements = document.querySelectorAll('.select2__selected.open');
                                            openedSelectedElements.forEach(($item) => {
                                                select2_when_to_close($item as HTMLElement, 'remove');
                                            });

                                            const openedMenus = document.querySelectorAll('.select2__menu');
                                            openedMenus.forEach(($item) => {
                                                ($item as HTMLElement).style.display = 'none';
                                            });
                                        // FECHAR SELECTS ABERTOS
                                    }
                                });
                            }
                        // KEYBOARD EVENTS
                    }
                }, 50, $rand);
            }


            // SELECT2 (PROPRIO)
                var $select2_offsetTop_cima = 72;
                var $select2_offsetTop_baixo = 300 - 30;
                function select2_own($e: Element): void
                {
                    select2_createCustomselect2($e as HTMLSelectElement);
                }

                function select2_createCustomselect2(select2: HTMLSelectElement): void
                {
                    let select = select2;
                    let options = select2.querySelectorAll('option');
                    let optionsArr = Array.prototype.slice.call(options);

                    let customselect2 = document.createElement('div');
                    customselect2.classList.add('select2');
                    select2.classList.forEach(function($value, $key){
                        if ($value!='design' && $value!='select2-ok'){
                            customselect2.classList.add($value);
                        }
                    })
                    select2.insertAdjacentElement('afterend', customselect2);

                    let selected_input = document.createElement('div');
                    selected_input.classList.add('select2__selected_input');
                    selected_input.innerHTML = '<input />';
                    customselect2.appendChild(selected_input);

                    let selected = document.createElement('div');
                    selected.classList.add('select2__selected');
                    if (select2.value && select2.querySelector(`option[value="${select2.value}"]`) != null){
                        const optionElement = select2.querySelector(`option[value="${select2.value}"]`);
                        selected.textContent = strip_tags(stripHtml(optionElement?.innerHTML.replace(/<\/?[^>]+(>|$)/g, "") || ``));
                    } else {
                        selected.textContent = strip_tags(optionsArr[0]?.textContent);
                    }
                    customselect2.appendChild(selected);

                    // SELECT EVENTS
                        // select.addEventListener("change", (e) => {
                        //     customselect2.value = e.target.value;
                        // });
                    // SELECT EVENTS

                    let menu = document.createElement('div');
                    menu.classList.add('select2__menu');
                    menu.classList.add('no_sel');
                    customselect2.appendChild(menu);
                    selected.addEventListener('click', select2_toggleselect2.bind(menu));

                    let search = document.createElement('input');
                    search.placeholder = 'Pesquisar...';
                    search.type = 'text';
                    search.classList.add('select2__menu_search');
                    menu.appendChild(search);

                    let menuItemsWrapper = document.createElement('div');
                    menuItemsWrapper.classList.add('select2__menu_items');
                    menu.appendChild(menuItemsWrapper);

                    let menuItemsWrapper_1 = document.createElement('div');
                    menuItemsWrapper_1.classList.add('select2__no_result');
                    let menuItemsWrapper_2 = document.createElement('div');
                    menuItemsWrapper_2.classList.add('select2__menu_item_no_result');
                    menuItemsWrapper_2.innerHTML = 'Nenhum resultado encontrado...';
                    menuItemsWrapper_1.appendChild(menuItemsWrapper_2);
                    menuItemsWrapper_1.style.display = 'none';
                    menu.appendChild(menuItemsWrapper_1);

                    select2_autocomplete_ini(select2, menu);

                    select2_items(optionsArr, select2, menu, selected, menuItemsWrapper);

                    search.addEventListener('input', select2_filterItems.bind(search, optionsArr, menu));

                    document.addEventListener('click', (e: MouseEvent) => select2_closeIfClickedOutside(menu, e, customselect2));
                    // document.addEventListener('click', select2_closeIfClickedOutside.bind(customselect2, menu, customselect2));

                    menuItemsWrapper.addEventListener('mouseover', select2_closeIfClickedOutside_hover.bind(customselect2, menu));

                    //select2.style.display = 'none';

                    select2_items_focus_tabs(select2, customselect2, menu);
                }

                // ITEMS
                    function select2_items(optionsArr: HTMLOptionElement[], select2: HTMLSelectElement, menu: HTMLElement, selected: HTMLElement, menuItemsWrapper: HTMLElement): void
                    {
                        optionsArr.forEach(option => {
                            let item = document.createElement('div');
                            item.classList.add('select2__menu_item');
                            item.dataset.value = option.value;
                            item.innerHTML = select2_option_name(select2, option.textContent as string);
                            menuItemsWrapper.appendChild(item);

                            if (select2.value === item.dataset.value) { // if (select2.value == item.getAttribute('data-value')){
                                item.classList.add('selected'); // (menuItemsWrapper.querySelector(`[data-value="${select2.value}"]`) as HTMLElement).classList.add('selected');
                            }

                            item.addEventListener('click', select2_setSelected.bind(item, selected, select2, menu));
                        });
                    }
                // ITEMS

                // FOCUS TABS
                    var $no_focus_select2 = 0
                    function select2_items_focus_tabs(select2: HTMLElement, customselect2: HTMLElement, menu: HTMLElement)
                    {
                        let $e = customselect2.querySelector(`.select2__selected_input input`) as HTMLElement;
                        if ($e){
                            $e.addEventListener("focus", (e) => {
                                let $e_1 = customselect2.querySelector(`.select2__menu`) as HTMLElement;
                                if ($e_1){
                                    // ABRIR
                                        //select2_open($e_1);
                                    // ABRIR
                                }
                            });
                            select2.addEventListener("focus", (e) => {
                                let $e_1 = customselect2.querySelector(`.select2__menu`) as HTMLElement;
                                if ($e_1 && !$no_focus_select2){
                                    // ABRIR
                                        //select2_open($e_1);
                                    // ABRIR
                                }
                                $no_focus_select2 = 0;
                            });
                        }
                    }
                // FOCUS TABS

                // QUANDO CLICA NO BOX PRINCIPAL (SELECT) PARA APARECER OS ITENS (OPTION)
                    function select2_toggleselect2(this: HTMLElement) // this
                    {
                        if (this.offsetParent !== null){
                            this.style.display = 'none';
                            select2_when_to_close(this.parentNode?.querySelector(".select2__selected") as HTMLElement, 'remove');

                        } else {
                            select2_open(this);
                        }
                    }
                // QUANDO CLICA NO BOX PRINCIPAL (SELECT) PARA APARECER OS ITENS (OPTION)

                // ABRIR SELECT
                    function select2_open($this: HTMLElement)
                    {
                        const $e_ = $this.parentNode?.querySelector('.select2__menu_search') as HTMLInputElement | null;
                        if ($e_) {
                            $e_.value = '';
                            $e_.dispatchEvent(new Event('input'));
                        }
                        $this.parentNode?.querySelector('.select2__menu_search')?.dispatchEvent(new Event('input'));

                        $this.style.display = 'block';
                        select2_when_to_close($this.parentNode?.querySelector(".select2__selected") as HTMLElement, 'add');
                        selected_first_option($this);

                        // VERIFICAR SE ABRE TOP OU BOTTOM
                            let $pos = document.body.getBoundingClientRect();
                            const parentNode = $this.parentNode as HTMLElement | null;

                            if (parentNode) {
                                const parentRect = parentNode.getBoundingClientRect();
                                if (parentRect.top > $pos.height + $pos.top - 280) {
                                    $this.classList.add('bottom');
                                } else {
                                    $this.classList.remove('bottom');
                                }
                            }
                        // VERIFICAR SE ABRE TOP OU BOTTOM

                        setTimeout(function () { ($this.querySelector('input') as HTMLInputElement)?.focus(); }, 50);
                    }
                // ABRIR SELECT


                // SELECIONANDO A PRIMEIRA OPCAO
                    function selected_first_option($this: HTMLElement)
                    {
                        document.querySelectorAll(".select2__menu .select2__menu_items .select2__menu_item").forEach(value => {
                            value.classList.remove('select2_dn');
                            value.classList.remove('select2__menu_item__hoverr');
                        });
                        let $e = $this.parentNode?.querySelector(".select2__menu .select2__menu_items .select2__menu_item.selected") as HTMLElement;
                        if ($e){
                            $e.classList.add('select2__menu_item__hoverr');

                            let $scroll_ = $e.offsetTop - $select2_offsetTop_cima;
                            $this.parentNode?.querySelector(".select2__menu .select2__menu_items")?.scrollTo({ top: $scroll_, behavior: 'smooth'});
                        }		        		
                    }
                // SELECIONANDO A PRIMEIRA OPCAO

                // QUANDO SELECIONA O ITEM
                    function select2_setSelected(this: HTMLElement, selected: HTMLElement, select2: HTMLInputElement | HTMLSelectElement, menu: HTMLElement) // this
                    {
                        let value = this.dataset.value || '';
                        let label = strip_tags(this.textContent || '');
        
                        selected.textContent = label;
                        select2.value = value;
                        select2.dispatchEvent(new Event('change'));

                        // QUANDO SELECIONA O ITEM
                            menu.style.display = 'none';
                            (menu.querySelector('.select2__no_result') as HTMLElement).style.display = 'none';
                            select2_when_to_close(menu.parentNode?.querySelector(".select2__selected") as HTMLElement, 'remove');
                            (menu.querySelector('input') as HTMLInputElement).value = '';
                            (menu.querySelectorAll('div') as NodeListOf<HTMLElement>).forEach(div => {
                                if (div.classList.contains('selected')){
                                    div.classList.remove('selected');
                                }
                                if (div.offsetParent === null){
                                    div.style.display = 'block';
                                }
                            });
                            this.classList.add('selected');

                            $no_focus_select2 = 1;
                            setTimeout(function(){ $no_focus_select2 = 0; }, 50);
                        // QUANDO SELECIONA O ITEM
                    }
                // QUANDO SELECIONA O ITEM

                // QUANDO DIGITA NO INPUT
                    function select2_filterItems(this: HTMLInputElement, itemsArr: HTMLElement[], menu: HTMLElement): void // this
                    {
                        let customOptions = menu.querySelectorAll('.select2__menu_items div') as NodeListOf<HTMLElement>;
                        let value = (this as HTMLInputElement).value.toLowerCase();
                        let filteredItems = itemsArr.filter(item => no_accent(item.innerHTML.toLowerCase()).includes(no_accent(value)));
                        let indexesArr = filteredItems.map(item => itemsArr.indexOf(item));

                        // FILTER (QUANDO DIGITA ALGO)
                            itemsArr.forEach(option => {
                                if (!indexesArr.includes(itemsArr.indexOf(option))){
                                    let $e = customOptions[itemsArr.indexOf(option)];
                                    if ($e){
                                        $e.style.display = 'none';
                                        $e.classList.add('select2_dn');
                                    }
                                } else {
                                    if (customOptions[itemsArr.indexOf(option)]){
                                        if (customOptions[itemsArr.indexOf(option)].offsetParent === null){
                                            let $e = customOptions[itemsArr.indexOf(option)];
                                            $e.style.display = 'block';
                                            $e.classList.remove('select2_dn');
                                        }
                                    }
                                }
                            });
                        // FILTER (QUANDO DIGITA ALGO)

                        // TIRANDO select2__menu_item__hoverr E COLOCANDO NO PRIMEIRO DA LISTA
                            let $e = menu.querySelector('.select2__menu_items .select2__menu_item:not(.select2_dn)') as HTMLElement;
                            if ($e){
                                (menu.querySelector('.select2__no_result') as HTMLElement).style.display = 'none';

                                (menu.querySelector('.select2__menu_items') as HTMLElement).scrollTo({ top: 0, behavior: 'smooth'});

                                (menu.querySelectorAll('.select2__menu_items .select2__menu_item') as NodeListOf<HTMLElement>).forEach(value => {
                                    value.classList.remove('select2__menu_item__hoverr');
                                });
                                $e.classList.add('select2__menu_item__hoverr');
                            } else {
                                (menu.querySelector('.select2__no_result') as HTMLElement).style.display = 'block';
                            }
                        // TIRANDO select2__menu_item__hoverr E COLOCANDO NO PRIMEIRO DA LISTA

                        select2_autocomplete__menu_items((menu.parentNode as HTMLElement)?.previousElementSibling as HTMLElement, menu);
                    }
                // QUANDO DIGITA NO INPUT

                // TIRAR CONFIGS QUANDO FECHAR
                    function select2_when_to_close($e: HTMLElement, $tipo: string): void
                    {
                        if ($tipo == 'add'){
                            $e.classList.add('open')
                        } else {
                            $e.classList.remove('open')
                        }
                        (($e.parentNode as HTMLElement).querySelector('.select2__no_result') as HTMLElement).style.display = 'none';

                        $no_focus_select2 = 1;
                        setTimeout(function(){ $no_focus_select2 = 0; }, 50);
                    }
                // TIRAR CONFIGS QUANDO FECHAR

                // QUANDO CLICA FORA DA TELA (FECHAR TUDO)
                    function select2_closeIfClickedOutside(menu: HTMLElement, e: MouseEvent, customselect2: HTMLElement): void
                    {
                        if (!e.target || !(e.target instanceof HTMLElement)) return;

                        if (!e.target.classList.contains('select2-ok') &&
                            !e.target.classList.contains('select2__menu') &&
                            !e.target.classList.contains('select2__menu_search') &&
                            !e.target.classList.contains('select2__menu_items') &&
                            !e.target.classList.contains('select2__menu_item') &&
                            !e.target.classList.contains('select2__no_result') &&
                            !e.target.classList.contains('select2__menu_item_no_result')) {

                            // FECHAR SELECT2 SE CLICAR FORA
                                if (e.target.closest('.select2__selected') === null && e.target !== customselect2 && menu.offsetParent !== null){
                                    menu.style.display = 'none';
                                    select2_when_to_close(menu.parentNode?.querySelector(".select2__selected") as HTMLElement, 'remove');

                                }
                            // FECHAR SELECT2 SE CLICAR FORA
                            // FECHAR OUTROS SELECT2 SE CLICAR EM ALGUM SELECT2
                                if (e.target !== customselect2.querySelector('.select2__selected')){
                                    menu.style.display = 'none';
                                    select2_when_to_close(menu.parentNode?.querySelector(".select2__selected") as HTMLElement, 'remove');
                                }
                            // FECHAR OUTROS SELECT2 SE CLICAR EM ALGUM SELECT2

                            // FECHAR SELECT2 SE CLICAR FORA
                                // const selectedElement = menu.parentNode?.querySelector('.select2__selected') as HTMLElement; // if (e.target.closest('.select2__selected') === null && e.target !== menu && menu.offsetParent !== null){
                                // if (selectedElement) { // 
                                //     menu.style.display = 'none';
                                //     select2_when_to_close((menu.parentNode?.querySelector(".select2__selected") as HTMLElement), 'remove');
                                // }
                            // FECHAR SELECT2 SE CLICAR FORA

                            // FECHAR OUTROS SELECT2 SE CLICAR EM ALGUM SELECT2
                                // if (e.target !== this.querySelector('.select2__selected') as HTMLElement){

                                // if (e.target !== menu.parentNode?.querySelector('.select2__selected') as HTMLElement){ // if (e.target !== this.querySelector('.select2__selected') as HTMLElement){
                                //     menu.style.display = 'none';
                                //     select2_when_to_close((menu.parentNode?.querySelector(".select2__selected") as HTMLElement), 'remove');
                                // }
                            // FECHAR OUTROS SELECT2 SE CLICAR EM ALGUM SELECT2

                            //select2_autocomplete__menu_items(menu.parentNode.querySelector(".select2__selected").parentNode.previousElementSibling, menu.parentNode.querySelector(".select2__selected").parentNode.querySelector('.select2__menu'));
                        }
                    }
                // QUANDO CLICA FORA DA TELA (FECHAR TUDO)

                // HOVERR
                    function select2_closeIfClickedOutside_hover(menu: HTMLElement, e: MouseEvent): void
                    {
                        if (!e.target || !(e.target instanceof HTMLElement)) return;

                        if (e.target.classList.contains('select2__menu_item')){
                            const $e_1 = e.target.parentNode?.querySelector(".select2__menu_item__hoverr") as HTMLElement;
                            if ($e_1){
                                $e_1.classList.remove('select2__menu_item__hoverr');
                            }
                            e.target.classList.add('select2__menu_item__hoverr');
                        }
                    }
                // HOVERR

                // OPTION NOME
                    function select2_option_name($select: HTMLElement, $nome: string): string
                    {
                        let result = $nome;
                        if ($select.getAttribute('name') == 'icone' || $select.getAttribute('name') == 'icones' || $select.getAttribute('name') == 'icones_1' || $select.getAttribute('name') == 'icones_2' || $select.getAttribute('name') == 'icones_3' || $select.getAttribute('name') == 'icones_4' || $select.getAttribute('name') == 'icones_5' || $select.getAttribute('name') == 'icones_6' || $select.getAttribute('name') == 'icones_7' || $select.getAttribute('name') == 'icones_8' || $select.getAttribute('name') == 'icones_9' || $select.getAttribute('name') == 'icones_10'){
                            result = `<i class="${result}"></i> &nbsp; ${result}`;
                        }
                        return result;
                    }
                // OPTION NOME


                // AUTO COMPLETE
                    function select2_autocomplete_ini(select2: HTMLElement, menu: HTMLElement): void
                    {
                        if (select2){
                            if (select2.hasAttribute('autocomplete')){
                                let menuItemsWrapper_3 = document.createElement('div');
                                menuItemsWrapper_3.classList.add('select2__autocomplete');
                                let menuItemsWrapper_4 = document.createElement('div');
                                menuItemsWrapper_4.classList.add('select2__menu_item_autocomplete');
                                menuItemsWrapper_4.innerHTML = 'Digite 2 ou mais caracteres';
                                menuItemsWrapper_3.appendChild(menuItemsWrapper_4);
                                menu.appendChild(menuItemsWrapper_3);
                            }
                        }
                    }
                    async function select2_autocomplete__menu_items(select2: HTMLElement, menu: HTMLElement): Promise<void>
                    {
                        if (select2){
                            if (select2.hasAttribute('autocomplete')){
                                let $length = (menu.querySelector('.select2__menu_search') as HTMLInputElement).value.length;
                                if ($length < 2){
                                    (menu.querySelector('.select2__menu_items') as HTMLElement).style.display = 'none';
                                    (menu.querySelector('.select2__no_result') as HTMLElement).style.display = 'none';
                                    (menu.querySelector('.select2__autocomplete') as HTMLElement).style.display = 'block';

                                } else {
                                    (menu.querySelector('.select2__autocomplete') as HTMLElement).style.display = 'none';
                                    (menu.querySelector('.select2__menu_items') as HTMLElement).style.display = 'block';

                                    // AJAX
                                        await api(`/${$_GET[0]}/autocomplete`, {module: $_GLOBAL.OBJ.menu_admin.id, table: select2.getAttribute('name'), busca: (menu.querySelector('.select2__menu_search') as HTMLInputElement).value}, function (json : any){
                                            let $option = '';
                                            json?.items?.map(function(value: any, $key: any){
                                                $option += `<option value="${value.id}">${value.name}</option>`;
                                            })

                                            select2.innerHTML = $option;
                                            let options_ = select2.querySelectorAll('option');
                                            let optionsArr_ = Array.prototype.slice.call(options_);
                                            (menu.querySelector('.select2__menu_items') as HTMLElement).innerHTML = '';

                                            let $selected = menu.parentNode?.querySelector('.select2__selected');
                                            select2_items(optionsArr_, select2 as HTMLSelectElement, menu, $selected as HTMLElement, (menu.querySelector('.select2__menu_items') as HTMLElement));

                                            // TIRANDO select2__menu_item__hoverr E COLOCANDO NO PRIMEIRO DA LISTA
                                                let $e = menu.querySelector('.select2__menu_items .select2__menu_item:not(.select2_dn)');
                                                if ($e){
                                                    (menu.querySelector('.select2__no_result') as HTMLElement).style.display = 'none';

                                                    (menu.querySelector('.select2__menu_items') as HTMLElement).scrollTo({ top: 0, behavior: 'smooth'});

                                                    menu.querySelectorAll('.select2__menu_items .select2__menu_item').forEach(value => {
                                                        value.classList.remove('select2__menu_item__hoverr');
                                                    });
                                                    $e.classList.add('select2__menu_item__hoverr');
                                                } else {
                                                    (menu.querySelector('.select2__no_result') as HTMLElement).style.display = 'block';
                                                }
                                            // TIRANDO select2__menu_item__hoverr E COLOCANDO NO PRIMEIRO DA LISTA

                                            load_close();
                                        }, 0);
                                    // AJAX
                                }
                            }
                        }
                    }
                // AUTO COMPLETE


                // KEYBOARD
                    function select2_keyboard_up_down($tipo: string)
                    {
                        let $e = document.querySelector(".select2__menu .select2__menu_items .select2__menu_item.select2__menu_item__hoverr") as HTMLElement;
                        if ($e){
                            let $e_1 = $tipo=='cima' ? $e.previousElementSibling as HTMLElement : $e.nextElementSibling as HTMLElement;

                            // verificando se a um filter (pegar proximo arquivo sem .select2_dn)
                            for(let $i = 0; $i < 1000; $i++){
                                if ($e_1 == null){ $i = 1000;
                                } else {
                                    if ($e_1.classList.contains('select2_dn')){
                                        $e_1 = $tipo=='cima' ? $e_1.previousElementSibling as HTMLElement : $e_1.nextElementSibling as HTMLElement;
                                    }
                                }
                            }

                            if ($e_1){
                                $e_1.focus();

                                let $scroll_ = $tipo=='cima' ? ($e.offsetTop - $select2_offsetTop_cima) : ($e.offsetTop - $select2_offsetTop_baixo);
                                ($e_1.parentNode as HTMLElement).scrollTo({ top: $scroll_, behavior: 'smooth'});

                                $e_1.classList.add('select2__menu_item__hoverr');
                                $e.classList.remove('select2__menu_item__hoverr');
                            }
                        }
                    }
                    function select2_keyboard_enter(e: KeyboardEvent): void
                    {
                        let $e = e.target as HTMLElement;
                        if ($e){
                            if ($e.classList.contains('select2__menu_search')){
                                e.preventDefault();
                                let $e_1 = document.querySelector(".select2__menu .select2__menu_items .select2__menu_item.select2__menu_item__hoverr");
                                if ($e_1){
                                    $e_1.dispatchEvent(new Event('click'));
                                }
                            }
                        }
                    }
                // KEYBOARD
            // SELECT2 (PROPRIO)







            // MULTIPLE
                export function select_multiple_search($e: HTMLInputElement)
                {
                    let $value = $e.value;
                    let $options = $e.parentNode?.querySelectorAll('select option') as NodeListOf<HTMLElement>;
                    let $itemsArr = Array.prototype.slice.call($options);

                    let $filteredItems = $itemsArr.filter(item => no_accent(item.innerHTML.toLowerCase()).includes(no_accent($value)));
                    let $indexesArr = $filteredItems.map(item => $itemsArr.indexOf(item));

                    $itemsArr.forEach(option => {
                        if (!$indexesArr.includes($itemsArr.indexOf(option))){
                            option.style.display = 'none';
                        } else {
                            option.style.display = 'block';
                        }
                    });

                }
                export function select_multiple_add($e: HTMLElement)
                {
                    let $select = $e.parentNode?.parentNode?.querySelector('select.multiple__temp') as HTMLSelectElement;
                    let $values = $select.querySelectorAll('select.multiple__temp option:checked') as NodeListOf<HTMLOptionElement>;
                    if ($values){
                        let $e_1 = $e.parentNode?.parentNode?.querySelector('select.multiple__real') as HTMLSelectElement;
                        select_multiple_mover($select, $e_1, $values);
                    }
                }
                export function select_multiple_remove($e: HTMLElement)
                {
                    let $select = $e.parentNode?.parentNode?.querySelector('select.multiple__real') as HTMLSelectElement;
                    let $values = $select.querySelectorAll('option:checked') as NodeListOf<HTMLOptionElement>;
                    if ($values){
                        let $e_1 = $e.parentNode?.parentNode?.querySelector('select.multiple__temp') as HTMLSelectElement;
                        select_multiple_mover($select, $e_1, $values);
                    }
                }
                function select_multiple_mover($select: HTMLSelectElement, $e_1: HTMLSelectElement, $values: NodeListOf<HTMLOptionElement>)
                {
                    if ($e_1){
                        $values.forEach($value => {
                            let $e_2 = $select.querySelectorAll(`option[value="${$value.value}"]`) as NodeListOf<HTMLOptionElement>;
                            if ($e_2){
                                $e_2.forEach(value => {
                                    $e_1.appendChild(value);
                                });
                            }
                        });
                        select_multiple_order($e_1);
                    }
                }
                function select_multiple_order($select: HTMLSelectElement)
                {
                    let $itemsArr = Array.prototype.slice.call($select);
                    $itemsArr.sort(function(a: HTMLOptionElement, b: HTMLOptionElement){
                        return a.text == b.text  ? 0 : a.text < b.text ? -1 : 1;
                    });

                    $select.innerHTML = '';
                    $itemsArr.map(function($val: HTMLOptionElement, $key: number){
                        $select.appendChild($val);
                    });
                }
                function select_multiple_keyboard_enter(e: KeyboardEvent): void
                {
                    let $e = e.target as HTMLElement;
                    if ($e){
                        if ($e.classList.contains('multiple__search') as boolean){
                            e.preventDefault();
                        }
                        if ($e.classList.contains("multiple__temp") as boolean){
                            e.preventDefault();
                            let $select = $e as HTMLSelectElement;
                            let $values = $select.querySelectorAll('option:checked') as NodeListOf<HTMLOptionElement>;
                            if ($values){
                                let $e_1 = $select.parentNode?.parentNode?.querySelector('select.multiple__real') as HTMLSelectElement;
                                if ($e_1){
                                    select_multiple_mover($select, $e_1, $values);
                                }
                            }
                        }
                        if ($e.classList.contains("multiple__real")){
                            e.preventDefault();
                            let $select = $e as HTMLSelectElement;
                            let $values = $select.querySelectorAll('option:checked') as NodeListOf<HTMLOptionElement>;
                            if ($values){
                                let $e_1 = $select.parentNode?.parentNode?.querySelector('select.multiple__temp') as HTMLSelectElement;
                                select_multiple_mover($select, $e_1, $values);
                            }
                        }
                    }
                }
            // MULTIPLE
        // SELECT2




















        // SORTABLE
            // $_GLOBAL.FORM.v.sortable = []; // add in code
            // $_GLOBAL.OBJ.sortable = `input`; // add in code

            var SORTABLE_LIST: any = [];
            var DROPPED: boolean= false;
            export function sortable($classe: string = '.sortable__'): void
            {
                setTimeout(() => { sortable__($classe) }, 50);
                setTimeout(() => { sortable__($classe) }, 1000);
                setTimeout(() => { sortable__($classe) }, 2000);
            }
            export function sortable__($classe: string): void
            {
                const containers = document.querySelectorAll(`${$classe}`) as NodeListOf<HTMLElement>;
                const draggables = document.querySelectorAll(`${$classe} > li`) as NodeListOf<HTMLElement>;

                draggables.forEach(draggable => {
                    if (!draggable.classList.contains('__DRAGGABLE__OK')){
                        draggable.classList.add('__DRAGGABLE__OK');

                        let $e = draggable.querySelector('.draggable_move') as HTMLElement;
                        if ($e){
                            draggable.classList.add('draggable');
                            $e.setAttribute('draggable', 'true');
                        }

                        draggable.addEventListener('dragstart', (e) => {
                            draggable.classList.add('dragging');
                        })

                        draggable.addEventListener('dragend', (e) => {
                            draggable.classList.remove('dragging');
                        })
                    }
                })

                containers.forEach(container => {
                    if (!container.classList.contains('__DRAGGABLE__OK')){
                        container.classList.add('__DRAGGABLE__OK');

                        container.addEventListener('dragover', (e: DragEvent) => {
                            if (e.target instanceof HTMLElement && e.target.tagName.toLowerCase() != 'input' && e.target.tagName.toLowerCase() != 'select'){
                                e.preventDefault()
                                const draggable = document.querySelector('.dragging') as HTMLElement;
                                // Verificar se existe um elemento dragging (não é um drag de tag)
                                if (draggable) {
                                    const afterElement = sortable_Drag(container, e.clientY);
                                    if (afterElement == null) {
                                        container.appendChild(draggable)
                                    } else {
                                        container.insertBefore(draggable, afterElement)
                                    }
                                }
                            }
                        })
                        container.addEventListener('drop', (e: DragEvent) => {
                            SORTABLE_LIST = [];
                            const draggables_new = document.querySelectorAll(`${$classe} > li`) as NodeListOf<HTMLElement>;
                            draggables_new.forEach(item => {
                                const dir = item.getAttribute('dir');
                                if (dir) SORTABLE_LIST.push(dir);

                                if ($_GLOBAL.FORM.v?.sortable != null){
                                    $_GLOBAL.FORM.v.sortable = SORTABLE_LIST;
                                }

                                DROPPED = true;
                                setTimeout(() => { 
                                    if (DROPPED){
                                        // if ($_GLOBAL.OBJ?.sortable && $_GLOBAL.FORM.v?.[$_GLOBAL.OBJ?.sortable]){
                                            // let inputs: Record<string, any> = {};
                                            // for (const [key, value] of Object.entries(SORTABLE_LIST as Record<string, string>)){
                                            //     if ($_GLOBAL.FORM.v[$_GLOBAL.OBJ.sortable]?.[value]){
                                            //         inputs[key] = $_GLOBAL.FORM.v[$_GLOBAL.OBJ.sortable]?.[value];
                                            //     }
                                            // }

                                            // let fields: string[] = [];
                                            // for (const [key, value] of Object.entries($_GLOBAL.FORM.v[$_GLOBAL.OBJ.sortable])) {
                                            //     if (value && typeof value === "object") {
                                            //         for (const [key_1, value_1] of Object.entries(value)) {
                                            //             if (!fields.includes(key_1)) {
                                            //                 fields.push(key_1);
                                            //             }
                                            //         }
                                            //     }
                                            // }

                                            // let inputs_new: Record<string, any> = {};
                                            // for (const [key, value] of Object.entries(inputs)) {
                                            //     if (!inputs_new[key]) {
                                            //         inputs_new[key] = {};
                                            //     }

                                            //     for (const [key_1, value_1] of Object.entries(fields)) {
                                            //         inputs_new[key][value_1] = value[value_1] ?? ``;
                                            //     }
                                            // }

                                            // console.log($_GLOBAL.FORM.v[$_GLOBAL.OBJ.sortable]);
                                            // console.log(inputs_new);

                                            // $_GLOBAL.FORM.v[$_GLOBAL.OBJ.sortable] = inputs_new;
                                        // }
                                    }
                                    DROPPED = false;
                                 }, 50);

                            })
                        });	
                    }
                })
            }
            function sortable_Drag(container: HTMLElement, y: number): HTMLElement | null
            {
                const draggableElements = [...container.querySelectorAll('.draggable:not(.dragging)') as NodeListOf<HTMLElement>]

                return draggableElements.reduce<{ offset: number, element: HTMLElement | null }>((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
        
                    if (offset < 0 && offset > closest.offset) {
                        return {
                            offset: offset,
                            element: child
                        };
                    } else {
                        return closest;
                    }
                }, {
                    offset: Number.NEGATIVE_INFINITY,
                    element: null
                }).element;
            }
        // SORTABLE




















        // TOOLTIP
            // TOOLTIP
                export function tooltip(): void
                {
                    setTimeout(() => {
                        if (!MOBILE()){
                            tooltip_hover();
                            tooltip_click();
                        }
                        tooltip_focus();
                    }, 50);
                }
                export function tooltip_remove(): void
                {
                    const tooltips = document.querySelectorAll('.tooltip__') as NodeListOf<HTMLElement>;
                    tooltips.forEach((tooltip) => tooltip.remove());
                }
            // TOOLTIP







            // HOVER
                function tooltip_hover(): void
                {
                    let $tooltip = document.querySelectorAll('[tooltip]');
                    $tooltip.forEach($item => {
                        let $val = $item.getAttribute('tooltip');
                        if ($val && $val != `false`){
                            if (!$item.classList.contains('tooltip-ok')){
                                $item.classList.add('tooltip-ok');

                                let $pos: string = 'top';
                                if ($item.getAttribute('pos')){
                                    $pos = $item.getAttribute('pos') || 'top';
                                }
                                tooltip_hover_($item, $pos);
                            }
                        }
                    });
                }
                function tooltip_hover_($item: Element, $pos: string='top', $txt: string=''): void
                {
                    $txt = $txt ? $txt : $item.getAttribute('tooltip') || '';
                    if ($txt){
                        let tooltip__ = new Balloon__hover($item, $txt, {position: $pos});
                        $item.addEventListener('mouseover', () => {
                            if (!tooltip__.isVisible){
                                tooltip__.show();
                            }
                        });
                        document.addEventListener('mouseout', () => {
                            if (tooltip__.isVisible){
                                tooltip__.destroy();
                            }
                        });
                    }
                }
                class Balloon__hover {
                    item_: Element;
                    position: string;
                    txt: string;
                    className: string;
                    orderedPositions: string[];
                    $tooltip__: HTMLDivElement;
                    handleWindowEvent: () => void;
                    handleDocumentEvent: (evt: Event) => void;

                    constructor(item_: Element, txt: string, {
                        position = 'top',
                        className = 'tooltip__'
                    }: {position?: string, className?: string}) {
                        this.item_ = item_;
                        this.position = position;
                        this.txt = txt;
                        this.className = className;
                        this.orderedPositions = ['top', 'right', 'bottom', 'left'];
                        this.$tooltip__ = document.createElement('div');
                        this.$tooltip__.innerHTML = this.txt;

                        Object.assign(this.$tooltip__.style, {
                            position: 'fixed'
                        });

                        this.$tooltip__.classList.add(className);

                        this.handleWindowEvent = () => {
                            if (this.isVisible) {
                                this.show();
                            }
                        };

                        this.handleDocumentEvent = (evt) => {
                            if (this.isVisible && evt.target !== this.item_ && evt.target !== this.$tooltip__) {
                                this.$tooltip__.remove();
                            }
                        };
                    }
                    get isVisible(): boolean {
                        return document.body.contains(this.$tooltip__);
                    }
                    show(): void {
                        //document.addEventListener('click', this.handleDocumentEvent);
                        //window.addEventListener('scroll', this.handleWindowEvent);
                        //window.addEventListener('resize', this.handleWindowEvent);

                        document.body.appendChild(this.$tooltip__);

                        const {
                            top: item_Top,
                            left: item_Left
                        } = this.item_.getBoundingClientRect();
                        const {
                            offsetHeight: item_Height,
                            offsetWidth: item_Width
                        } = this.item_ as HTMLElement;
                        const {
                            offsetHeight: $tooltip__Height,
                            offsetWidth: $tooltip__Width
                        } = this.$tooltip__;

                        const positionIndex = this.orderedPositions.indexOf(this.position);

                        const positions: Record<string, { name: string; top: number; left: number }> = {
                            top: {
                                name: 'top',
                                top: item_Top - $tooltip__Height,
                                left: item_Left - (($tooltip__Width - item_Width) / 2)
                            },
                            right: {
                                name: 'right',
                                top: item_Top - (($tooltip__Height - item_Height) / 2),
                                left: item_Left + item_Width
                            },
                            bottom: {
                                name: 'bottom',
                                top: item_Top + item_Height,
                                left: item_Left - (($tooltip__Width - item_Width) / 2)
                            },
                            left: {
                                name: 'left',
                                top: item_Top - (($tooltip__Height - item_Height) / 2),
                                left: item_Left - $tooltip__Width
                            }
                        };

                        const position = this.orderedPositions
                            .slice(positionIndex)
                            .concat(this.orderedPositions.slice(0, positionIndex))
                            .map(pos => positions[pos])
                            .find(pos => {
                                this.$tooltip__.style.top = `${pos.top}px`;
                                this.$tooltip__.style.left = `${pos.left}px`;
                                return tooltip__isInViewport(this.$tooltip__);
                            });

                        this.orderedPositions.forEach(pos => {
                            this.$tooltip__.classList.remove(`${this.className}--${pos}`);
                        });

                        if (position) {
                            this.$tooltip__.classList.add(`${this.className}--${position.name}`);
                        } else {
                            this.$tooltip__.style.top = `${positions.bottom.top}px`;
                            this.$tooltip__.style.left = `${positions.bottom.left}px`;
                            this.$tooltip__.classList.add(`${this.className}--bottom`);
                        }
                    }
                    destroy(): void {
                        this.$tooltip__.remove();

                        //document.removeEventListener('click', this.handleDocumentEvent);
                        //window.removeEventListener('scroll', this.handleWindowEvent);
                        //window.removeEventListener('resize', this.handleWindowEvent);
                    }
                    toggle(): void {
                        if (this.isVisible) {
                            this.destroy();
                        } else {
                            this.show();
                        }
                    }
                }
                function tooltip__isInViewport(element: HTMLElement): boolean
                {
                    const rect = element.getBoundingClientRect();
                    const html = document.documentElement;
                    return rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || html.clientHeight) &&
                        rect.right <= (window.innerWidth || html.clientWidth);
                }
            // HOVER








            // FOCUS
                function tooltip_focus(): void {
                    const $tooltip = document.querySelectorAll('[tooltip_focus]');
                    $tooltip.forEach(($item) => {
                        if (!$item.classList.contains('tooltip_focus-ok')) {
                            $item.classList.add('tooltip_focus-ok');
                            tooltip_focus_($item as HTMLElement, 'top');
                        }
                    });
                }

                function tooltip_focus_($item: HTMLElement, $pos: string = 'top', $txt: string = '') {
                    $txt = $txt ? $txt : $item.getAttribute('tooltip_focus') || '';
                    if ($txt) {
                        let tooltip__ = new Balloon__focus($item, $txt, { position: $pos });
                        (tooltip__ as any).focus = 0;
                        $item.addEventListener('focus', () => {
                            if (!tooltip__.isVisible) {
                                tooltip__.show();
                            }
                        });
                        $item.addEventListener('blur', () => {
                            if (!(tooltip__ as any).focus && !(tooltip__ as any).click && tooltip__.isVisible) {
                                tooltip__.destroy();
                            }
                        });
                    }
                }

                function tooltip__focus__mostrar($item_1: HTMLElement, $target: EventTarget | null): number {
                    let result = 0;
                    if ($item_1 === $target) { result = 1; }
                    if ($item_1 === ($target as HTMLElement)?.parentNode) { result = 1; }
                    if ($item_1 === ($target as HTMLElement)?.parentNode?.parentNode) { result = 1; }
                    return result;
                }

                function tooltip__focus__remover_all(tooltip__: Balloon__focus) {
                    document.querySelectorAll('.tooltip__').forEach(($i) => {
                        if ($i !== tooltip__.$tooltip__) {
                            $i.remove();
                        }
                    });
                }

                class Balloon__focus {
                    item_: HTMLElement;
                    position: string;
                    txt: string;
                    className: string;
                    orderedPositions: string[];
                    $tooltip__: HTMLDivElement;

                    constructor(item_: HTMLElement, txt: string, {
                        position = 'top',
                        className = 'tooltip__'
                    }: { position?: string; className?: string }) {
                        this.item_ = item_;
                        this.position = position;
                        this.txt = txt;
                        this.className = className;
                        this.orderedPositions = ['top', 'right', 'bottom', 'left'];
                        this.$tooltip__ = document.createElement('div');
                        this.$tooltip__.innerHTML = this.txt;

                        Object.assign(this.$tooltip__.style, {
                            position: 'fixed'
                        });

                        this.$tooltip__.classList.add(className);
                    }

                    get isVisible(): boolean {
                        return document.body.contains(this.$tooltip__);
                    }

                    show(): void {
                        document.body.appendChild(this.$tooltip__);

                        const {
                            top: item_Top,
                            left: item_Left
                        } = this.item_.getBoundingClientRect();
                        const {
                            offsetHeight: item_Height,
                            offsetWidth: item_Width
                        } = this.item_;
                        const {
                            offsetHeight: $tooltip__Height,
                            offsetWidth: $tooltip__Width
                        } = this.$tooltip__;

                        const positionIndex = this.orderedPositions.indexOf(this.position);

                        const positions = {
                            top: {
                                name: 'top',
                                top: item_Top - $tooltip__Height,
                                left: item_Left - (($tooltip__Width - item_Width) / 2)
                            },
                            right: {
                                name: 'right',
                                top: item_Top - (($tooltip__Height - item_Height) / 2),
                                left: item_Left + item_Width
                            },
                            bottom: {
                                name: 'bottom',
                                top: item_Top + item_Height,
                                left: item_Left - (($tooltip__Width - item_Width) / 2)
                            },
                            left: {
                                name: 'left',
                                top: item_Top - (($tooltip__Height - item_Height) / 2),
                                left: item_Left - $tooltip__Width
                            }
                        };

                        const position = this.orderedPositions
                            .slice(positionIndex)
                            .concat(this.orderedPositions.slice(0, positionIndex))
                            .map(pos => positions[pos as keyof typeof positions])
                            .find(pos => {
                                this.$tooltip__.style.top = `${pos.top}px`;
                                this.$tooltip__.style.left = `${pos.left}px`;
                                return tooltip__isInViewport_focus(this.$tooltip__);
                            });

                        this.orderedPositions.forEach(pos => {
                            this.$tooltip__.classList.remove(`${this.className}--${pos}`);
                        });

                        if (position) {
                            this.$tooltip__.classList.add(`${this.className}--${position.name}`);
                        } else {
                            this.$tooltip__.style.top = `${positions.bottom.top}px`;
                            this.$tooltip__.style.left = `${positions.bottom.left}px`;
                            this.$tooltip__.classList.add(`${this.className}--bottom`);
                        }
                    }

                    destroy(): void {
                        this.$tooltip__.remove();
                    }

                    toggle(): void {
                        if (this.isVisible) {
                            this.destroy();
                        } else {
                            this.show();
                        }
                    }
                }

                function tooltip__isInViewport_focus(element: HTMLElement): boolean {
                    const rect = element.getBoundingClientRect();
                    const html = document.documentElement;
                    return rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || html.clientHeight) &&
                        rect.right <= (window.innerWidth || html.clientWidth);
                }
            // FOCUS








            // CLICK
                function tooltip_click(): void {
                    let $tooltip = document.querySelectorAll('[tooltip_click]');
                    $tooltip.forEach($item => {
                        if (!$item.classList.contains('tooltip_click-ok')) {
                            $item.classList.add('tooltip_click-ok');

                            let $pos = 'top';
                            if ($item.getAttribute('pos')) {
                                $pos = $item.getAttribute('pos') || 'top';
                            }
                            tooltip_click_($item as HTMLElement, $pos);
                        }
                    });
                }

                function tooltip_click_($item: HTMLElement, $pos: string = 'top', $txt: string = '') {
                    $txt = $txt ? $txt : $item.getAttribute('tooltip_click') || '';
                    if ($txt) {
                        let tooltip__ = new Balloon__click($item, $txt, { position: $pos });
                        $item.addEventListener('click', () => {
                            if (!tooltip__.isVisible && !$item.classList.contains(`tooltip_click_on`)) {
                                tooltip__.show();
                                setTimeout(() => { $item.classList.add(`tooltip_click_on`); }, 100);
                            }
                        });
                        document.addEventListener('click', (evt: Event) => {
                            if (tooltip__.isVisible && $item.classList.contains(`tooltip_click_on`)) {
                                tooltip__.destroy();
                                $item.classList.remove(`tooltip_click_on`);
                            }
                        });
                    }
                }

                function tooltip__click__remover_all(): void {
                    document.querySelectorAll('.tooltip__').forEach($i => {
                        $i.remove();
                    });
                }

                class Balloon__click {
                    item_: HTMLElement;
                    position: string;
                    txt: string;
                    className: string;
                    orderedPositions: string[];
                    $tooltip__: HTMLDivElement;

                    constructor(item_: HTMLElement, txt: string, {
                        position = 'top',
                        className = 'tooltip__'
                    }: { position?: string; className?: string }) {
                        this.item_ = item_;
                        this.position = position;
                        this.txt = txt;
                        this.className = className;
                        this.orderedPositions = ['top', 'right', 'bottom', 'left'];
                        this.$tooltip__ = document.createElement('div');
                        this.$tooltip__.innerHTML = this.txt;

                        Object.assign(this.$tooltip__.style, {
                            position: 'fixed'
                        });

                        this.$tooltip__.classList.add(className);
                    }

                    get isVisible(): boolean {
                        return document.body.contains(this.$tooltip__);
                    }

                    show(): void {
                        document.body.appendChild(this.$tooltip__);

                        const {
                            top: item_Top,
                            left: item_Left
                        } = this.item_.getBoundingClientRect();
                        const {
                            offsetHeight: item_Height,
                            offsetWidth: item_Width
                        } = this.item_;
                        const {
                            offsetHeight: $tooltip__Height,
                            offsetWidth: $tooltip__Width
                        } = this.$tooltip__;

                        const positionIndex = this.orderedPositions.indexOf(this.position);

                        const positions = {
                            top: {
                                name: 'top',
                                top: item_Top - $tooltip__Height,
                                left: item_Left - (($tooltip__Width - item_Width) / 2)
                            },
                            right: {
                                name: 'right',
                                top: item_Top - (($tooltip__Height - item_Height) / 2),
                                left: item_Left + item_Width
                            },
                            bottom: {
                                name: 'bottom',
                                top: item_Top + item_Height,
                                left: item_Left - (($tooltip__Width - item_Width) / 2)
                            },
                            left: {
                                name: 'left',
                                top: item_Top - (($tooltip__Height - item_Height) / 2),
                                left: item_Left - $tooltip__Width
                            }
                        } as Record<string, { name: string; top: number; left: number }>;

                        const position = this.orderedPositions
                            .slice(positionIndex)
                            .concat(this.orderedPositions.slice(0, positionIndex))
                            .map(pos => positions[pos])
                            .find(pos => {
                                this.$tooltip__.style.top = `${pos.top}px`;
                                this.$tooltip__.style.left = `${pos.left}px`;
                                return tooltip__click__isInViewport(this.$tooltip__);
                            });

                        this.orderedPositions.forEach(pos => {
                            this.$tooltip__.classList.remove(`${this.className}--${pos}`);
                        });

                        if (position) {
                            this.$tooltip__.classList.add(`${this.className}--${position.name}`);
                        } else {
                            this.$tooltip__.style.top = `${positions.bottom.top}px`;
                            this.$tooltip__.style.left = `${positions.bottom.left}px`;
                            this.$tooltip__.classList.add(`${this.className}--bottom`);
                        }
                    }

                    destroy(): void {
                        this.$tooltip__.remove();
                    }

                    toggle(): void {
                        if (this.isVisible) {
                            this.destroy();
                        } else {
                            this.show();
                        }
                    }
                }

                function tooltip__click__isInViewport(element: HTMLElement): boolean {
                    const rect = element.getBoundingClientRect();
                    const html = document.documentElement;
                    return rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || html.clientHeight) &&
                        rect.right <= (window.innerWidth || html.clientWidth);
                }
            // CLICK
        // TOOLTIP

    // PLUGINS




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // APP
        // HREF
            export function href_APP()
            {
                if (APP()){
                    setTimeout(() => {
                        document.querySelectorAll('a[href], a[href__]').forEach($item => {
                            let $link = $item.getAttribute('href');
                            if (!$link) $link = $item.getAttribute('href__');

                            // BLANK
                                if ($item.getAttribute('target') == '_blank'){
                                    $item.addEventListener("click", (e) => {
                                        if ($link != '/'){
                                            $link = replace(DIR(), '', $link ?? '');
                                        }

                                        e.preventDefault();
                                        if ($link && $link != null && $link != undefined && $link != 'null' && $link != 'undefined'){
                                            open_blank($link);
                                        }
                                    });
                                }
                            // BLANK

                            // ROUTER
                                else {
                                    $item.addEventListener("click", (e) => {
                                        if ($link != '/'){
                                            $link = replace(DIR(), '', $link ?? '');
                                        }

                                        e.preventDefault();
                                        if ($link && $link != null && $link != undefined && $link != 'null' && $link != 'undefined'){
                                            open__($link);
                                        }
                                    });
                                }
                            // ROUTER
                        });
                    }, 50);
                }
            }
        // HREF
    // APP




















    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // VERSIONS
        // 1.0.0
            // SHOW.BOXS -> SHOW.BOXS_FIXED
            // $_GLOBAL.SHOW.BOXS_FIXED -> $_GLOBAL.SHOW.BOXS_FIXED_TEXT
        // 1.0.0
    // VERSIONS