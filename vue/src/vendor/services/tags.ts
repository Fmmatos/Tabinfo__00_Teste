import { clone, compare__, compare__ini, replace, split, zipcode__ } from '@/vendor/services/events';





    // TAGS
        export function tags(value: any, type: string, attr: boolean = true): { attributes: any, events: any } {
            let attributes: any = { };
            let events: any = { };

            // ATTRIBUTES
                if (attr){
                    // TYPE
                        if (type == `text`){
                            attributes.type = value?.type ? value.type : `text`;
                        }
                    // TYPE

                    // NAME
                        if (type != `checkbox`){
                            attributes.name = value.name;
                        }
                    // NAME

                    // CLASS
                        if (type != `checkbox` && type != `radio`){
                            attributes.class = (compare__(`designx`, value?.class) || compare__(`designx`, value?.tags) || compare__(`design_no`, value?.class) || compare__(`design_no`, value?.tags) || compare__(`default`, value?.class) || compare__(`default`, value?.tags)) ?  `` : `design `;
                            attributes.class += `${value?.class ? value.class : ``}`;

                            if (type == `select`){
                                // attributes.class = `designx `;
                            }
                        }
                    // CLASS

                    // STYLE
                        if (value.style != null){
                            attributes.style = value.style;
                        }
                    // STYLE

                    // MIN
                        if (value.min != null){
                            attributes.min = value.min;
                        }
                    // MIN

                    // MINLENGTH
                        if (value.minlength != null){
                            attributes.minlength = value.minlength;
                        }
                    // MINLENGTH

                    // MAX
                        if (value.max != null){
                            attributes.max = value.max;
                        }
                    // MAX

                    // MAXLENGTH
                        if (value.maxlength != null){
                            attributes.maxlength = value.maxlength;
                        }
                    // MAXLENGTH

                    // PLACEHOLDER
                        if (value.placeholder != null){
                            attributes.placeholder = value.placeholder;
                        }
                    // PLACEHOLDER

                    // READONLY
                        if (value.readonly != null){
                            attributes.readonly = value.readonly;
                        }
                    // READONLY

                    // DISABLED
                        if (value.disabled != null){
                            attributes.disabled = value.disabled;
                        }
                    // DISABLED

                    // REQUIRED
                        if (value.required != null){
                            if (type != `checkbox`){
                                attributes.required = value.required;
                            }
                        }
                    // REQUIRED

                    // TOOLTIP
                        if (type != `checkbox` && type != `radio`){
                            if (value.tooltip != null){
                                attributes.tooltip = value.tooltip;
                            }
                        }
                        if ((type == `text` || type == `textarea`) && value?.extra){
                            if (!compare__ini(`|->`, value.extra)){
                                attributes.tooltip = value.extra;
                            }
                        }
                    // TOOLTIP

                    // TAGS
                        if (value.tags != null) {
                            let clone__ = transformAttributes(clone(value.tags));
                            const transformed = clone__.replace(
                                /"([^"]*)"/g,
                                (_, inside) => `"${inside.replace(/ /g, 'zxzxz')}"`
                            );
                            transformed.split(/\s+/).forEach((tag: string) => {
                                const [key, val] = tag.split('=');
                                let val__ = val ? val.replace(/['"]/g, ``) : ``;

                                if (key){
                                    val__ = replace('zyzyz', ' ', val__);
                                    val__ = replace('zxzxz', ' ', val__);
                                    
                                    if (attributes?.[key]){
                                        attributes[key] += ` ${val__}`;
                                    } else {
                                        attributes[key] = val__;
                                    }
                                }
                            });
                            if (type == `checkbox`){
                                attributes['required'] = null;
                            }
                        }
                    // TAGS
                }
            // ATTRIBUTES


            // EVENTS
                if (!attr){
                    // CLICK
                        if (typeof value.click === 'function'){
                            events.click = (event: Event) => value.click();
                        }
                    // CLICK

                    // __INPUT
                        if (value.name == `zipcode` && split(`change`, value?.tags)){
                            // events.input = () => zipcode__($_GLOBAL.FORM, $_GLOBAL.OBJ);
                            events.blur = () => zipcode__($_GLOBAL.FORM, $_GLOBAL.OBJ);
                        }
                        if (typeof value.input === 'function'){
                            events.input = (event: Event) => value.input();
                        }
                    // __INPUT

                    // __KEYUP
                        if (typeof value.keyup === 'function'){
                            events.keyup = (event: KeyboardEvent) => value.keyup();
                        }
                    // __KEYUP

                    // __KEYDOWN
                        if (typeof value.keydown === 'function'){
                            events.keydown = (event: KeyboardEvent) => value.keydown();
                        }
                    // __KEYDOWN

                    // __CHANGE
                        if (typeof value.change === 'function'){
                            if (type == `select`){
                                events.change = (event: Event) => value.change();

                            } else {
                                events.focusout = (event: Event) => value.change();
                            }
                        }
                    // __CHANGE

                    // __FOCUS
                        if (typeof value.focus === 'function'){
                            events.focus = (event: Event) => value.focus();
                        }
                    // __FOCUS

                    // __BLUR
                        if (typeof value.blur === 'function'){
                            events.blur = (event: Event) => value.blur();
                        }
                    // __BLUR
                }
            // EVENTS

            return { attributes, events };
        }

        function transformAttributes(input: string) {
            // Substituir partes do atributo style
            const updatedStyle = input.replace(/style="([^"]*?)"/, (match, group) => {
                const transformedStyle = group.replace(/\s/g, 'zyzyz');
                return `style="${transformedStyle}"`;
            });

            // Substituir classes
            const updatedClass = updatedStyle.replace(/class="([^"]*?)"/, (match, group) => {
                const transformedClass = group.replace(/\s/g, 'zyzyz');
                return `class="${transformedClass}"`;
            });

            return updatedClass;
        }
    // TAGS










    // OTHERS
        // ACCEPT__
            export function accept__(PROPS: { tags?: string; name?: string } = {}): string {
                let $return = '';

                if (
                    (PROPS.name === 'image' || compare__(`image_`, String(PROPS?.name)) || compare__(`image`, String(PROPS?.tags)))
                    && (PROPS.name !== 'file' && !compare__(`file_`, String(PROPS?.name)) && !compare__(`file`, String(PROPS?.tags)))
                ) {
                    $return = 'image/*';
                }

                return $return;
            }
        // ACCEPT__
    // OTHERS
