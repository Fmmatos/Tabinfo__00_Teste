<script setup lang="ts">
import SELECT__ from '@/vendor/components/input/select.vue';
import TEXT__ from '@/vendor/components/input/text.vue';

import Z_CART_SUMARY__ from '@/vendor/pages_dashboard/z_cart_sumary.vue';

import api from '@/vendor/services/api';
import { MercadoPago__js } from '@/vendor/services/pay';
import { address_1, address_2, address_3, address_4, alerts, base64_encode, card_brand, card_validate, cart_type, compare__, copy__, count, date__, load, load_close, MOBILE, open__, price, refresh, tooltip, upper } from '@/vendor/services/events';
import { DIR_API, DIR_P } from '@/vendor/services/localhost';
import { inject } from 'vue';
tooltip();

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // SHOW
        SHOW.address_new_edit = false;
        SHOW.cards_new_edit = false;
        SHOW.card_verse = false;
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
    // EVENTS

    // FUNCTIONS
        // ADDRESS
            const __address_new = () => {
                FORM.v = {};
                OBJ.address_edit = {};
                OBJ.address_edit.name = OBJ.user.name;
                OBJ.address_edit.phone = OBJ.user.phone;
                SHOW.address_new_edit = true;
            }
            const __address_edit = (value: any) => {
                FORM.v = {};
                OBJ.address_edit = value;
                FORM.v.main = value.main === 1 ? true : false;
                FORM.v.city = value.city;
                FORM.v.uf = value.uf;
                SHOW.address_new_edit = true;
            }
            const __submit_address = () => {
                let id = OBJ.address_edit?.id ? OBJ.address_edit.id : 0;
                FORM.v._method = 'PUT';
                api(`/dashboard/address${id ? `/${id}/update` : `/store`}`, { FORM: FORM }, (json: any) => {
                    api(`/dashboard/cart/position/address_reset`, OBJ.address_edit);
                    SHOW.address_new_edit = false;
                });
            }
            const __address_delete = (value: any) => {
                if(confirm(`Tem certeza que deseja excluir este endereço?`)){
                    FORM.v.id = value.id;
                    FORM.v._method = 'DELETE';
                    api(`/dashboard/address/delete`, { FORM: FORM }, (json: any) => {
                        api(`/dashboard/cart/position/address_reset`, value);
                        SHOW.address_new_edit = false;
                    });
                }
            }
        // ADDRESS


        // SHIPPING
            const __cart_shipping = (value: any) => {
                let form: any = {};
                form.shipping = value.id;
                form._method = 'PUT';
                api(`/dashboard/cart/save`, form);
            }
        // SHIPPING


        // PAY
            // CARDS
                const __cards_new = () => {
                    FORM.v = {};
                    OBJ.cards_edit = {};
                    FORM.v.address_shipping = false;
                    OBJ.cards_edit.phone = OBJ.user.phone;
                    OBJ.cards_edit.birth = OBJ.user.birth;
                    OBJ.cards_edit.email = OBJ.user.email;
                    SHOW.cards_new_edit = true;
                }
                const __submit_cards = () => {
                    let card_validate__ = card_validate(FORM.v.card_number, FORM.v.card_validate);
                    if (typeof card_validate__ === "object" && "error" in card_validate__) {
                        alerts(0, card_validate__.error);
                        return;
                    }

                    let id = OBJ.cards_edit?.id ? OBJ.cards_edit.id : 0;
                    FORM.v._method = 'PUT';
                    api(`/dashboard/cards${id ? `/${id}/update` : `/store`}`, { FORM: FORM }, (json: any) => {
                        SHOW.cards_new_edit = false;
                        __cart_position(`cards`, json.SAVE);
                        setTimeout(() => {  
                            FORM.v.card_cvv = json.SAVE.card_cvv;
                        }, 1000);
                    });
                }
                const __cards_delete = (value: any) => {
                    if(confirm(`Tem certeza que deseja excluir este cartão?`)){
                        FORM.v.id = value.id;
                        FORM.v._method = 'DELETE';
                        api(`/dashboard/cards/delete`, { FORM: FORM }, (json: any) => {
                            refresh();
                            SHOW.cards_new_edit = false;
                        });
                    }
                }
                const __cards_address_shipping = () => {
                    setTimeout(() => {
                        FORM.v.zipcode = '';
                        FORM.v.address = '';
                        FORM.v.number = '';
                        FORM.v.neighborhood = '';
                        FORM.v.complement = '';
                        FORM.v.city = '';
                        FORM.v.uf = '';
                        if(FORM.v.address_shipping){
                            FORM.v.zipcode = OBJ.CART?.address?.zipcode;
                            FORM.v.address = OBJ.CART?.address?.address;
                            FORM.v.number = OBJ.CART?.address?.number;
                            FORM.v.neighborhood = OBJ.CART?.address?.neighborhood;
                            FORM.v.complement = OBJ.CART?.address?.complement;
                            FORM.v.city = OBJ.CART?.address?.city;
                            FORM.v.uf = OBJ.CART?.address?.uf;
                        }
                    }, .5);
                }
            // CARDS


            const __pay = async () => {
                let ok = 1;
                let form: any = {};

                // CARD_CREDIT
                    if(OBJ.CART?.pay?.method == `card_credit`){
                        if(!FORM.v.card_cvv){
                            ok = 0;
                            alerts(0, `Preencha o código de segurança do cartão!`);
                        }
                        form.card_cvv = FORM.v.card_cvv;
                        form.card_installments = FORM.v.card_installments>1 ? FORM.v.card_installments : 1;

                        if(ok && 0){
                            load();
                            let response = await MercadoPago__js();
                            if(response?.token && response?.issuer_id && response?.payment_method_id){
                                load();
                                form.card_token = response.token;
                                form.card_issuer_id = response.issuer_id;
                                form.card_payment_method_id = response.payment_method_id;

                            } else {
                                ok = 0;
                                alerts(0, `Este cartão está inválido, tente novamente com outro cartão!`);
                                load_close();
                            }

                        } else {
                            console.log($_GLOBAL.OBJ.mercado_pago_cards);
                            form.card_name = FORM.v.card_name;
                            form.card_number = FORM.v.card_number;
                            form.card_month = FORM.v.card_month;
                            form.card_year = FORM.v.card_validate;
                        }
                    }
                // CARD_CREDIT

                if(ok){
                    form._method = 'PUT';
                    api(`/dashboard/pay/${OBJ.CART?.pay?.method}`, form, (json: any) => {
                        if(json.url){
                            open__(`/cart${json.url}`, {}, 1);
                        }
                    });
                }
            }
        // PAY


        // POSITION
            const __cart_position = (type: string, value: any = {}) => {
                if(compare__(`pay_method_`, type)){
                    FORM.v.city = ``;
                    FORM.v.card_cvv = ``;
                    FORM.v.card_installments = 1;
                }
                value.card_installments = FORM.v.card_installments ?? 1;

                api(`/dashboard/cart/position/${type}`, value, (json: any) => {
                    if(type == `cards`){
                        FORM.v.card_cvv = '';
                        FORM.v.card_installments = 1;
                    }
                }, type==`card_installments` ? 0 : 1);
            }
        // POSITION
    // FUNCTIONS
</script>


<template>

    <div>

        <div class="posr p20 pl35 pr35 bdt_E2E8F0 bdb_E2E8F0 bg_fff">
            <div class="centerr_0">
                <div class="flexx flex_j flex_ac">
                    <div class="fz28 fwb8">Carrinho</div>
                    <div class=""><svg width="100" viewBox="0 0 150 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_51_2647)"> <rect width="150" height="32" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4326 23.8947V14.3157C16.4326 13.56 15.8195 12.9473 15.0632 12.9473H4.10815C3.35186 12.9473 2.73876 13.56 2.73876 14.3157V23.8947C2.73876 24.6504 3.35186 25.2631 4.10815 25.2631H15.0632C15.8195 25.2631 16.4326 24.6504 16.4326 23.8947ZM19.1713 14.3157V23.8947C19.1713 26.1619 17.3321 27.9999 15.0632 27.9999H4.10815C1.83929 27.9999 -9.91753e-08 26.1619 0 23.8947L4.19004e-07 14.3157C5.18179e-07 12.0484 1.83929 10.2104 4.10815 10.2104H15.0632C17.3321 10.2104 19.1713 12.0484 19.1713 14.3157Z" fill="black"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M9.58593 6.10549C8.07335 6.10549 6.84716 7.33083 6.84716 8.84234V11.5792H4.1084V8.84234C4.1084 5.8193 6.56076 3.36865 9.58593 3.36865C12.6111 3.36865 15.0635 5.8193 15.0635 8.84234V11.5792H12.3247V8.84234C12.3247 7.33083 11.0985 6.10549 9.58593 6.10549Z" fill="black"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M10.9546 17.0527V21.158H8.21582V17.0527H10.9546Z" fill="black"/> <path d="M38.9934 0.185059C39.9945 0.185059 40.8473 0.345619 41.5518 0.666743C42.2563 0.987866 42.794 1.45102 43.1647 2.05622C43.5355 2.6614 43.7209 3.39629 43.7209 4.26085C43.7209 5.1254 43.5355 5.86029 43.1647 6.46548C42.794 7.07067 42.2563 7.53382 41.5518 7.85495C40.8473 8.17608 39.9945 8.33664 38.9934 8.33664H36.5647V12.4124H33.376V0.185059H38.9934ZM38.4928 5.94674C39.1602 5.94674 39.6608 5.81088 39.9945 5.53916C40.3405 5.25509 40.5136 4.82898 40.5136 4.26085C40.5136 3.69271 40.3405 3.27277 39.9945 3.00106C39.6608 2.71698 39.1602 2.57495 38.4928 2.57495H36.5647V5.94674H38.4928Z" fill="black"/> <path d="M55.6343 12.4124H52.3528L51.5 9.65201H47.3101L46.4387 12.4124H43.2314L47.5326 0.185059H51.3517L55.6343 12.4124ZM47.9034 7.48443H50.9067L49.405 2.5379L47.9034 7.48443Z" fill="black"/> <path d="M61.6653 12.5979C60.5777 12.5979 59.6074 12.3571 58.7546 11.8754C57.9143 11.3937 57.2592 10.6835 56.7895 9.74484C56.3199 8.80618 56.085 7.66371 56.085 6.31747C56.085 4.99592 56.3322 3.86582 56.8265 2.92716C57.321 1.9885 58.0255 1.26597 58.94 0.759579C59.867 0.253192 60.9547 0 62.2029 0C63.5873 0 64.7058 0.253192 65.5586 0.759579C66.4114 1.25361 67.0727 2.04408 67.5423 3.13095L64.5945 4.29811C64.4339 3.66821 64.1496 3.21123 63.7417 2.92716C63.3338 2.64308 62.8272 2.50105 62.2215 2.50105C61.6159 2.50105 61.0968 2.64926 60.6642 2.94568C60.2317 3.22976 59.9041 3.65587 59.6816 4.224C59.4591 4.77979 59.3479 5.47144 59.3479 6.29895C59.3479 7.1635 59.4591 7.88603 59.6816 8.46653C59.9165 9.04702 60.2563 9.47929 60.7013 9.76337C61.1587 10.0351 61.7209 10.1709 62.3883 10.1709C62.7468 10.1709 63.0743 10.1277 63.3709 10.0413C63.6675 9.95481 63.9271 9.83129 64.1496 9.67074C64.372 9.49782 64.5451 9.28787 64.6687 9.04084C64.7924 8.78147 64.8541 8.47887 64.8541 8.13305V7.94779H62.0546V5.81726H67.5608V12.4126H65.3917L65.1507 9.54105L65.6698 9.98568C65.4102 10.8255 64.9407 11.474 64.2608 11.9309C63.5934 12.3756 62.7283 12.5979 61.6653 12.5979Z" fill="black"/> <path d="M81.1255 12.4124H77.844L76.9912 9.65201H72.8013L71.93 12.4124H68.7227L73.0238 0.185059H76.8429L81.1255 12.4124ZM73.3946 7.48443H76.3979L74.8963 2.5379L73.3946 7.48443Z" fill="black"/> <path d="M96.7129 0.185059V12.4124H93.8949V6.79895L93.9876 3.03811H93.9505L90.9286 12.4124H88.3516L85.3297 3.03811H85.2926L85.3853 6.79895V12.4124H82.5488V0.185059H87.091L89.019 6.4099L89.705 9.00358H89.7421L90.4466 6.42843L92.3561 0.185059H96.7129Z" fill="black"/> <path d="M99.4219 12.4124V0.185059H108.951V2.64906H102.611V5.13159H107.653V7.44737H102.611V9.94843H109.192V12.4124H99.4219Z" fill="black"/> <path d="M122.149 0.185059V12.4124H118.608L114.807 5.79853L113.881 3.98295H113.862L113.936 6.24316V12.4124H111.118V0.185059H114.659L118.46 6.79895L119.387 8.61453H119.405L119.331 6.35432V0.185059H122.149Z" fill="black"/> <path d="M135.149 0.185059V2.64906H131.163V12.4124H127.974V2.64906H123.97V0.185059H135.149Z" fill="black"/> <path d="M141.952 0C143.188 0 144.251 0.253192 145.141 0.759579C146.043 1.25361 146.735 1.96997 147.217 2.90863C147.699 3.84729 147.94 4.9774 147.94 6.29895C147.94 7.6205 147.699 8.7506 147.217 9.68926C146.735 10.6279 146.043 11.3505 145.141 11.8568C144.251 12.3509 143.188 12.5979 141.952 12.5979C140.716 12.5979 139.647 12.3509 138.745 11.8568C137.843 11.3505 137.15 10.6279 136.668 9.68926C136.186 8.7506 135.945 7.6205 135.945 6.29895C135.945 4.9774 136.186 3.84729 136.668 2.90863C137.15 1.96997 137.843 1.25361 138.745 0.759579C139.647 0.253192 140.716 0 141.952 0ZM141.952 2.50105C141.359 2.50105 140.858 2.64308 140.45 2.92716C140.042 3.21123 139.734 3.63734 139.523 4.20547C139.313 4.76126 139.208 5.45908 139.208 6.29895C139.208 7.12645 139.313 7.82429 139.523 8.39242C139.734 8.96056 140.042 9.38666 140.45 9.67074C140.858 9.95481 141.359 10.0968 141.952 10.0968C142.545 10.0968 143.04 9.95481 143.435 9.67074C143.843 9.38666 144.152 8.96056 144.362 8.39242C144.572 7.82429 144.677 7.12645 144.677 6.29895C144.677 5.45908 144.572 4.76126 144.362 4.20547C144.152 3.63734 143.843 3.21123 143.435 2.92716C143.04 2.64308 142.545 2.50105 141.952 2.50105Z" fill="black"/> <path d="M35.4442 30.939V23.3096C35.4442 23.0851 35.4442 22.8548 35.4442 22.619C35.4554 22.372 35.4666 22.1138 35.4779 21.8443C35.0958 22.2148 34.6576 22.5348 34.1633 22.8043C33.68 23.0625 33.1802 23.2477 32.6632 23.3601L32.3262 21.7938C32.5621 21.7601 32.8374 21.6814 33.152 21.558C33.4667 21.4345 33.7925 21.2772 34.1295 21.0864C34.4666 20.8956 34.7756 20.6935 35.0565 20.4801C35.3375 20.2556 35.5566 20.0366 35.7138 19.8232H37.2307V30.939H35.4442Z" fill="black"/> <path d="M44.0954 31.1074C42.7583 31.1074 41.7078 30.6246 40.9437 29.659C40.191 28.6822 39.8145 27.2561 39.8145 25.3811C39.8145 23.5061 40.191 22.0856 40.9437 21.12C41.7078 20.1432 42.7583 19.6548 44.0954 19.6548C45.4437 19.6548 46.4943 20.1432 47.247 21.12C48.0112 22.0856 48.3931 23.5061 48.3931 25.3811C48.3931 27.2561 48.0112 28.6822 47.247 29.659C46.4943 30.6246 45.4437 31.1074 44.0954 31.1074ZM44.0954 29.5748C44.6347 29.5748 45.0842 29.4232 45.4437 29.12C45.8145 28.8056 46.0898 28.3398 46.2695 27.7222C46.4606 27.0934 46.556 26.313 46.556 25.3811C46.556 24.4492 46.4606 23.6745 46.2695 23.0569C46.0898 22.4282 45.8145 21.9622 45.4437 21.659C45.0842 21.3446 44.6347 21.1874 44.0954 21.1874C43.556 21.1874 43.101 21.3446 42.7302 21.659C42.3707 21.9622 42.101 22.4282 41.9212 23.0569C41.7415 23.6745 41.6515 24.4492 41.6515 25.3811C41.6515 26.313 41.7415 27.0934 41.9212 27.7222C42.101 28.3398 42.3707 28.8056 42.7302 29.12C43.101 29.4232 43.556 29.5748 44.0954 29.5748Z" fill="black"/> <path d="M54.6784 31.1074C53.3413 31.1074 52.2908 30.6246 51.5267 29.659C50.774 28.6822 50.3975 27.2561 50.3975 25.3811C50.3975 23.5061 50.774 22.0856 51.5267 21.12C52.2908 20.1432 53.3413 19.6548 54.6784 19.6548C56.0267 19.6548 57.0773 20.1432 57.83 21.12C58.5942 22.0856 58.9761 23.5061 58.9761 25.3811C58.9761 27.2561 58.5942 28.6822 57.83 29.659C57.0773 30.6246 56.0267 31.1074 54.6784 31.1074ZM54.6784 29.5748C55.2177 29.5748 55.6672 29.4232 56.0267 29.12C56.3975 28.8056 56.6729 28.3398 56.8525 27.7222C57.0436 27.0934 57.139 26.313 57.139 25.3811C57.139 24.4492 57.0436 23.6745 56.8525 23.0569C56.6729 22.4282 56.3975 21.9622 56.0267 21.659C55.6672 21.3446 55.2177 21.1874 54.6784 21.1874C54.139 21.1874 53.684 21.3446 53.3132 21.659C52.9537 21.9622 52.684 22.4282 52.5042 23.0569C52.3245 23.6745 52.2345 24.4492 52.2345 25.3811C52.2345 26.313 52.3245 27.0934 52.5042 27.7222C52.684 28.3398 52.9537 28.8056 53.3132 29.12C53.684 29.4232 54.139 29.5748 54.6784 29.5748Z" fill="black"/> <path d="M62.9692 30.939L70.2501 19.8232H71.8006L64.5366 30.939H62.9692ZM63.4074 19.6548C63.9804 19.6548 64.4749 19.784 64.8905 20.0422C65.3176 20.3003 65.6434 20.6598 65.8681 21.12C66.104 21.5803 66.222 22.125 66.222 22.7537C66.222 23.3713 66.104 23.9158 65.8681 24.3874C65.6434 24.8477 65.3176 25.2071 64.8905 25.4653C64.4749 25.7235 63.9804 25.8527 63.4074 25.8527C62.8456 25.8527 62.3513 25.7235 61.9242 25.4653C61.4973 25.2071 61.1658 24.8477 60.9299 24.3874C60.7052 23.9158 60.5928 23.3713 60.5928 22.7537C60.5928 22.125 60.7052 21.5803 60.9299 21.12C61.1658 20.6598 61.4973 20.3003 61.9242 20.0422C62.3513 19.784 62.8456 19.6548 63.4074 19.6548ZM63.4074 20.9685C63.1266 20.9685 62.8849 21.0414 62.6827 21.1874C62.4804 21.3222 62.3287 21.5243 62.2276 21.7937C62.1265 22.0519 62.0759 22.3719 62.0759 22.7537C62.0759 23.1243 62.1265 23.4443 62.2276 23.7137C62.3287 23.9832 62.4804 24.1853 62.6827 24.32C62.8849 24.4548 63.1266 24.5222 63.4074 24.5222C63.6996 24.5222 63.9467 24.4548 64.149 24.32C64.3512 24.1853 64.5029 23.9832 64.604 23.7137C64.7051 23.4443 64.7557 23.1243 64.7557 22.7537C64.7557 22.3719 64.7051 22.0519 64.604 21.7937C64.5029 21.5243 64.3512 21.3222 64.149 21.1874C63.9467 21.0414 63.6996 20.9685 63.4074 20.9685ZM71.3624 24.9095C71.9355 24.9095 72.43 25.0387 72.8456 25.2969C73.2727 25.5551 73.5984 25.92 73.8231 26.3916C74.0591 26.8519 74.177 27.3909 74.177 28.0085C74.177 28.6372 74.0591 29.1819 73.8231 29.6422C73.5984 30.1024 73.2727 30.4619 72.8456 30.72C72.43 30.9782 71.9355 31.1074 71.3624 31.1074C70.8007 31.1074 70.3064 30.9782 69.8793 30.72C69.4524 30.4619 69.1209 30.1024 68.8849 29.6422C68.6602 29.1819 68.5478 28.6372 68.5478 28.0085C68.5478 27.3909 68.6602 26.8519 68.8849 26.3916C69.1209 25.92 69.4524 25.5551 69.8793 25.2969C70.3064 25.0387 70.8007 24.9095 71.3624 24.9095ZM71.3624 26.24C71.0816 26.24 70.84 26.3074 70.6377 26.4422C70.4355 26.5769 70.2838 26.779 70.1827 27.0485C70.0815 27.3067 70.031 27.6267 70.031 28.0085C70.031 28.379 70.0815 28.699 70.1827 28.9685C70.2838 29.2379 70.4355 29.4456 70.6377 29.5916C70.84 29.7264 71.0816 29.7937 71.3624 29.7937C71.6547 29.7937 71.9018 29.7264 72.104 29.5916C72.3063 29.4456 72.4579 29.2379 72.5591 28.9685C72.6602 28.699 72.7107 28.379 72.7107 28.0085C72.7107 27.6379 72.6602 27.3179 72.5591 27.0485C72.4579 26.779 72.3063 26.5769 72.104 26.4422C71.9018 26.3074 71.6547 26.24 71.3624 26.24Z" fill="black"/> <path d="M85.7241 19.6548C86.724 19.6548 87.5893 19.8456 88.3196 20.2274C89.0498 20.5979 89.6679 21.1482 90.1735 21.8779L88.96 23.04C88.5331 22.3888 88.0499 21.9229 87.5106 21.6422C86.9825 21.3503 86.3532 21.2043 85.6229 21.2043C85.0836 21.2043 84.6398 21.2772 84.2915 21.4232C83.9431 21.5692 83.6847 21.7656 83.5162 22.0127C83.3589 22.2485 83.2802 22.5179 83.2802 22.8211C83.2802 23.1692 83.3982 23.4724 83.6342 23.7306C83.8814 23.9888 84.3365 24.1909 84.9993 24.3369L87.2578 24.8422C88.3364 25.0779 89.1004 25.4372 89.5499 25.92C89.9994 26.4029 90.224 27.0148 90.224 27.7558C90.224 28.4408 90.0387 29.0358 89.6679 29.5411C89.2971 30.0464 88.7802 30.4337 88.1173 30.7032C87.4656 30.9727 86.696 31.1074 85.8083 31.1074C85.0217 31.1074 84.3139 31.0064 83.6847 30.8043C83.0556 30.6022 82.5049 30.3271 82.033 29.979C81.5611 29.6309 81.1735 29.2324 80.8701 28.7832L82.1173 27.5369C82.3533 27.9298 82.6511 28.2835 83.0106 28.5979C83.3701 28.9011 83.7859 29.1369 84.2578 29.3053C84.741 29.4737 85.2746 29.5579 85.8589 29.5579C86.3758 29.5579 86.8196 29.4961 87.1903 29.3727C87.5724 29.2492 87.8589 29.0695 88.0499 28.8337C88.2521 28.5867 88.3533 28.2948 88.3533 27.9579C88.3533 27.6324 88.2409 27.3461 88.0162 27.099C87.8026 26.8519 87.3982 26.6611 86.8027 26.5264L84.3589 25.9706C83.6847 25.8246 83.1285 25.6169 82.6903 25.3474C82.2521 25.0779 81.9264 24.7524 81.7128 24.3706C81.4993 23.9776 81.3926 23.5398 81.3926 23.0569C81.3926 22.4282 81.5611 21.8611 81.8982 21.3558C82.2466 20.8393 82.7465 20.4295 83.3982 20.1264C84.0499 19.8119 84.8252 19.6548 85.7241 19.6548Z" fill="black"/> <path d="M92.7451 30.939V19.8232H100.801V21.3727H94.5485V24.5727H99.4361V26.0885H94.5485V29.3896H101.037V30.939H92.7451Z" fill="black"/> <path d="M107.779 31.1074C106.734 31.1074 105.83 30.8772 105.066 30.4169C104.301 29.9566 103.706 29.3053 103.279 28.4632C102.852 27.6098 102.639 26.5824 102.639 25.3811C102.639 24.2022 102.858 23.1861 103.296 22.3327C103.745 21.4793 104.375 20.8224 105.184 20.3622C106.004 19.8906 106.942 19.6548 107.998 19.6548C109.155 19.6548 110.088 19.8682 110.796 20.2948C111.515 20.7214 112.088 21.384 112.515 22.2822L110.847 23.0737C110.633 22.4561 110.279 21.9958 109.785 21.6927C109.302 21.3782 108.712 21.2211 108.015 21.2211C107.318 21.2211 106.706 21.384 106.178 21.7095C105.661 22.0351 105.257 22.5124 104.965 23.1411C104.672 23.7587 104.526 24.5053 104.526 25.3811C104.526 26.2682 104.655 27.0261 104.914 27.6548C105.172 28.2724 105.56 28.744 106.077 29.0695C106.605 29.3951 107.251 29.5579 108.015 29.5579C108.431 29.5579 108.818 29.5074 109.178 29.4064C109.537 29.294 109.852 29.1369 110.122 28.9348C110.391 28.7214 110.599 28.4576 110.745 28.1432C110.903 27.8176 110.981 27.4358 110.981 26.9979V26.6779H107.745V25.2127H112.582V30.939H111.234L111.133 28.699L111.47 28.8674C111.2 29.5748 110.751 30.125 110.122 30.5179C109.504 30.9109 108.723 31.1074 107.779 31.1074Z" fill="black"/> <path d="M124.324 19.8232V26.678C124.324 28.1488 123.936 29.2548 123.161 29.9959C122.385 30.7369 121.256 31.1075 119.773 31.1075C118.312 31.1075 117.189 30.7369 116.402 29.9959C115.627 29.2548 115.239 28.1488 115.239 26.678V19.8232H117.043V26.459C117.043 27.5032 117.267 28.278 117.717 28.7832C118.166 29.2885 118.852 29.5411 119.773 29.5411C120.705 29.5411 121.397 29.2885 121.846 28.7832C122.296 28.278 122.52 27.5032 122.52 26.459V19.8232H124.324Z" fill="black"/> <path d="M131.941 19.8232C133.132 19.8232 134.076 20.1208 134.772 20.7159C135.48 21.3109 135.834 22.1138 135.834 23.1243C135.834 24.1685 135.48 24.9769 134.772 25.5496C134.076 26.1109 133.132 26.3917 131.941 26.3917L131.772 26.4927H129.194V30.939H127.407V19.8232H131.941ZM131.806 24.9938C132.537 24.9938 133.076 24.8477 133.424 24.5559C133.784 24.2527 133.963 23.7924 133.963 23.1748C133.963 22.5685 133.784 22.1138 133.424 21.8106C133.076 21.5075 132.537 21.3559 131.806 21.3559H129.194V24.9938H131.806ZM132.868 25.3643L136.491 30.939H134.418L131.317 26.0717L132.868 25.3643Z" fill="black"/> <path d="M143.25 19.6548C144.329 19.6548 145.262 19.885 146.048 20.3453C146.835 20.8056 147.441 21.4624 147.868 22.3158C148.295 23.1692 148.509 24.1909 148.509 25.3811C148.509 26.5713 148.295 27.593 147.868 28.4464C147.441 29.2998 146.835 29.9566 146.048 30.4169C145.262 30.8772 144.329 31.1074 143.25 31.1074C142.183 31.1074 141.256 30.8772 140.469 30.4169C139.683 29.9566 139.076 29.2998 138.649 28.4464C138.222 27.593 138.009 26.5713 138.009 25.3811C138.009 24.1909 138.222 23.1692 138.649 22.3158C139.076 21.4624 139.683 20.8056 140.469 20.3453C141.256 19.885 142.183 19.6548 143.25 19.6548ZM143.25 21.2211C142.554 21.2211 141.953 21.384 141.447 21.7095C140.953 22.0351 140.571 22.5067 140.301 23.1243C140.031 23.7419 139.896 24.494 139.896 25.3811C139.896 26.2569 140.031 27.0092 140.301 27.6379C140.571 28.2555 140.953 28.7271 141.447 29.0527C141.953 29.3782 142.554 29.5411 143.25 29.5411C143.958 29.5411 144.559 29.3782 145.054 29.0527C145.559 28.7271 145.947 28.2555 146.217 27.6379C146.486 27.0092 146.621 26.2569 146.621 25.3811C146.621 24.494 146.486 23.7419 146.217 23.1243C145.947 22.5067 145.559 22.0351 145.054 21.7095C144.559 21.384 143.958 21.2211 143.25 21.2211Z" fill="black"/> </g> <defs> <clipPath id="clip0_51_2647"> <rect width="150" height="32" fill="white"/> </clipPath> </defs> </svg></div>
                </div>
            </div>
        </div>

        <div class="pt40 pb40 __CART__">
            <div class="centerr_0">

                <!-- CART -->
                    <div v-if="OBJ.CART?.items && OBJ.CART?.items?.length!=0" class="flexx_1000 gap_40">

                        <!-- PRODUCTS -->
                            <!-- MOBILE -->
                                <Z_CART_SUMARY__ v-if="MOBILE()" />
                            <!-- MOBILE -->
                        <!-- PRODUCTS -->





                        <!-- ACTIONS -->
                            <div class="flex_11 br10">
                                <div class="wr6 pr20 p0_1000">
                                    <!-- LOGIN -->
                                        <!-- bg_E1F8E0 -->
                                        <div class="posr p30 pl20 pr20 mb40 bg_fff br10 shadow" :class="cart_type(`login`)==0 ? `op5` : ``">
                                            <!-- <a class="posa t0 r0 p10 mt10 mr10 fz16 c_999"><i class="faa-pencil"></i></a> -->

                                            <div class="flexx flex_ac">
                                                <div class="w26 h26 fz14 fwb6 flexx flex_c flex_ac c_fff br50p" :class="cart_type(`login`)==2 ? `b_degrade_green` : `b_degrade_black`">1</div>
                                                <div class="pl10 fz18 fwb6" :class="cart_type(`login`)==2 ? `c_green` : ``">Identifique-se</div>
                                                <div v-if="cart_type(`login`)==2" class="pl10 fz18 fwb6 c_green"><i class="faa-check"></i></div>
                                            </div>

                                            <div v-if="cart_type(`login`) == 0" class="pt10 fz14"></div>

                                            <div v-else-if="cart_type(`login`) == 1" class="pt20"></div>

                                            <div v-else-if="cart_type(`login`) == 2" class="pt20">
                                                <div class="fz14 fwb6">{{ OBJ.user.name }}</div>
                                                <div class="pt10 fz14">{{ OBJ.user.email }}</div>
                                                <div class="pt10 fz14">CPF: {{ OBJ.user.cpf }}</div>
                                            </div>
                                        </div>
                                    <!-- LOGIN -->





                                    <!-- ADDRESS -->
                                        <div class="posr p30 pl20 pr20 mb40 bg_fff br10 shadow" :class="cart_type(`address`)==0 ? `op5` : ``">
                                            <a v-if="cart_type(`address`)==2" @click="__cart_position(`shipping_show`)" class="posa t0 r0 p10 mt10 mr10 fz16 c_999"><i class="faa-pencil"></i></a>

                                            <div class="flexx flex_ac">
                                                <div class="w26 h26 fz14 fwb6 flexx flex_c flex_ac c_fff br50p" :class="cart_type(`address`)==2 ? `b_degrade_green` : `b_degrade_black`">2</div>
                                                <div class="pl10 fz18 fwb6" :class="cart_type(`address`)==2 ? `c_green` : ``">Entrega</div>
                                                <div v-if="cart_type(`address`)==2" class="pl10 fz18 fwb6 c_green"><i class="faa-check"></i></div>
                                            </div>

                                            <div v-if="cart_type(`address`) == 0" class="pt10 fz13">Preencha suas informações pessoais para continuar.</div>

                                            <div v-else-if="cart_type(`address`) == 1" class="pt20">
                                                <div class="fz13">Cadastre ou selecione um endereço</div>

                                                <!-- NEW -->
                                                    <div v-if="SHOW.address_new_edit==true || OBJ.address?.length==0" class="pt20">
                                                        <div v-if="OBJ.address?.length!=0" class="pb20"><a @click="SHOW.address_new_edit = false" class="fz13"><i class="pr5 faa-angle-left"></i> Voltar</a></div>

                                                        <form @submit.prevent="__submit_address()">
                                                            <div class="wr6">
                                                                <TEXT__ label="CEP" name="zipcode" mask="zipcode" :value="OBJ?.address_edit?.zipcode" placeholder="00.000-000" tags="change" icon="search" icon_click="zipcode" required />
                                                            </div>
                                                            <div class="clear"></div>

                                                            <div class="">
                                                                <label class="pt10 flexx flex_ac">
                                                                    <input type="checkbox" v-model="FORM.v.main" value="1" />
                                                                    <span class="pl5">Marcar como endereço principal</span>
                                                                </label>
                                                            </div>

                                                            <div v-show="FORM.v.city">
                                                                <div class="pt20 fz14">{{ FORM.v.city }} / {{ FORM.v.uf }}</div>

                                                                <div class="pt20">
                                                                    <TEXT__ label="Endereço" name="address" :value="OBJ?.address_edit?.address" required />
                                                                </div>
                                                                <div class="pt20">
                                                                    <div class="wr4"><TEXT__ label="Número" name="number" :value="OBJ?.address_edit?.number" required /></div>
                                                                    <div class="wr8  pl20"><TEXT__ label="Bairro" name="neighborhood" :value="OBJ?.address_edit?.neighborhood" required /></div>
                                                                    <div class="clear"></div>
                                                                </div>
                                                                <div class="pt20">
                                                                    <div class="pb5">Complemento: <span class="c_777">(opional)</span></div>
                                                                    <TEXT__ name="complement" :value="OBJ?.address_edit?.complement" />
                                                                </div>

                                                                <div class="pt20">
                                                                    <TEXT__ label="Destinatário" name="name" :value="OBJ?.address_edit?.name" required />
                                                                </div>

                                                                <div class="pt20">
                                                                    <TEXT__ label="Telefone para contato" name="phone" :value="OBJ?.address_edit?.phone" required />
                                                                </div>

                                                                <div class="pt20">
                                                                    <button type="submit" class="w100p h40 fz15i bd0i br15 button_cart_1" >Salvar</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                <!-- NEW -->


                                                <div v-else>
                                                    <!-- LIST -->
                                                        <div>
                                                            <div class="pt20"><a @click="__address_new()" class="p5 pl10 pr10 fz13 bd_333 bg_hover_eee br5">+ Novo endereço</a></div>
                                                            <div class="pt15">
                                                                <div v-for="(value, key) in OBJ?.address" class="mb10 br5" :class="value.id == OBJ.CART?.address?.id ? `bd_1 bg_F4F6F8` : `bd_ccc bd_hover_1 bg_fff bg_hover_F4F6F8`">
                                                                    <div class="pl20 pr20 flexx flex_j flex_ac">
                                                                        <a @click="__cart_position(`address`, value)" class="w20 pt10 pb10 mr10">
                                                                            <div v-if="value.id == OBJ.CART?.address?.id" class="w16 h16 bdw5 bd_1 bg_fff br50p"></div>
                                                                            <div v-else class="w16 h16 flexx flex_c flex_ac bd_ccc br50p"></div>
                                                                        </a>
                                                                        <a @click="__cart_position(`address`, value)" class="flex_1 pt10 pb10">
                                                                            <div class="fwb5 limit">{{ value.name }} {{ value.main ? `(Principal)` : `` }}</div>
                                                                            <div class="pt1 fz11 c_666 limit">{{ address_1(value) }} {{ address_2(value) ? ` - ${address_2(value)}` : `` }}</div>
                                                                            <div class="pt1 fz11 c_666 limit">{{ address_3(value)}} | CEP: {{ address_4(value)}}</div>
                                                                        </a>
                                                                        <div class="pt10 pb10 tac">
                                                                            <a @click="__address_edit(value)" class="dib p5 fz18 vam c_blue"><i class="faa-edit"></i></a>
                                                                            <a @click="__address_delete(value)" class="dib p5 pb7 fz18 vam c_red"><i class="faa-times"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <!-- LIST -->


                                                    <!-- SHIPPING -->
                                                        <div v-if="OBJ.CART?.address?.id">
                                                            <div class="pt20 mt20 fz13 bdt_eee">Escolha uma forma de entrega:</div>
                                                            <div class="pt20">
                                                                <div v-for="(value, key) in OBJ?.shipping" class="mb10 br5" :class="value.id == OBJ.CART?.shipping?.id ? `bd_1 bg_F4F6F8` : `bd_ccc bd_hover_1 bg_fff bg_hover_F4F6F8`">
                                                                    <a @click="__cart_shipping(value)" class="p10 pl20 pr20 flexx flex_j flex_ac">
                                                                        <div class="w20 mr10">
                                                                            <div v-if="value.id == OBJ.CART?.shipping?.id" class="w16 h16 bdw5 bd_1 bg_fff br50p"></div>
                                                                            <div v-else class="w16 h16 flexx flex_c flex_ac bd_ccc br50p"></div>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="fwb6">{{ value.name }}</div>
                                                                            <div v-if="value.dias" class="pt4">{{ value.dias==1 ? `até ${value.dias} dia útil` : `até ${value.dias} dias úteis` }}</div>
                                                                        </div>
                                                                        <div class="flex_1 tar">
                                                                            <div v-if="value.error">
                                                                                <div class="fz12 c_red">{{ value.error }}</div>
                                                                            </div>
                                                                            <div v-else-if="value.price__>0">
                                                                                <div class="fz13 fwb6">R$ {{ value.price }}</div>
                                                                            </div>
                                                                            <div v-else>
                                                                                <div v-if="value.price__>0" class="c_999"><s>R$ {{ value.price }}</s></div>
                                                                                <div class="pt4 fwb6 c_green">Grátis</div>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                                <div v-if="OBJ.shipping?.length==0" class="pt10 pb10 fz13">Nenhum frete encontrado...</div>
                                                            </div>
                                                            <div v-if="OBJ.CART?.shipping?.id" class="pt20">
                                                                <a @click="refresh()" type="submit" class="w100p h40 fz15i flexx flex_c flex_ac bd0i br15 button_cart_1" >Continuar <i class="h23 pl10 fz20 faa-long-arrow-right"></i></a>
                                                            </div>
                                                        </div>
                                                    <!-- SHIPPING -->
                                                </div>
                                            </div>

                                            <div v-else-if="cart_type(`address`) == 2" class="pt20">
                                                <div v-if="OBJ.CART?.address?.city" class="">
                                                    <div class="fz14 fwb6">Endereço de entrega:</div>
                                                    <div class="pt10 fz13">{{ OBJ.CART?.address?.name }} | {{ OBJ.CART?.address?.phone }}</div>
                                                    <div class="pt5 fz13">{{ OBJ.CART?.address?.address }}, {{ OBJ.CART?.address?.number }} {{ OBJ.CART?.address?.complement }} - {{ OBJ.CART?.address?.neighborhood }}</div>
                                                    <div class="pt5 fz13">{{ OBJ.CART?.address?.city }}-{{ OBJ.CART?.address?.uf }} | CEP: {{ OBJ.CART?.address?.zipcode }}</div>
                                                </div>

                                                <div v-if="OBJ.CART?.shipping?.price__" class="pt20">
                                                    <div class="fz14 fwb6">Forma de entrega:</div>
                                                    <div class="pt10 fz13">
                                                        <div v-if="OBJ.CART?.shipping?.price__ && OBJ.CART.shipping.price__>0">{{ `${OBJ.CART?.shipping?.name ? `(${OBJ.CART?.shipping?.name})` : ``} R$&nbsp;${OBJ.CART.shipping.price}` }} <span v-if="OBJ.CART.shipping?.dias>0">- Prazo: até {{ OBJ.CART.shipping.dias }} dias úteis</span></div>
                                                        <div v-else-if="OBJ.CART?.shipping?.price__">{{ `${OBJ.CART?.shipping?.name ? `(${OBJ.CART?.shipping?.name})` : ``}` }} <span class="fwb6 c_green">Grátis</span> <span v-if="OBJ.CART.shipping?.dias>0">- Prazo: até {{ OBJ.CART.shipping.dias }} dias úteis</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <!-- ADDRESS -->                                        
                                </div>





                                <div class="wr6 pl20 p0_1000">
                                    <!-- PAY -->
                                        <div class="p30 pl20 pr20 mb40 bg_fff br10 shadow" :class="cart_type(`pay`)==0 ? `op5` : ``">
                                            <div class="flexx flex_ac">
                                                <div class="w26 h26 fz14 fwb6 flexx flex_c flex_ac c_fff b_degrade_black br50p">3</div>
                                                <div class="flex_1 pl10 fz18 fwb6">Pagamento</div>
                                            </div>

                                            <div v-if="cart_type(`pay`) == 0" class="pt10 fz13">Preencha suas informaçõe de entrega para continuar.</div>

                                            <div v-else class="pt20">
                                                <div class="fz13">Escolha uma forma de pagamento</div>

                                                <div class="pt20">
                                                    <!-- CARD_CREDIT -->
                                                        <div v-if="count(OBJ.CART?.pay?.card_credit)" class="mb10 br5" :class="OBJ.CART?.pay?.method == 'card_credit' ? `bd_1 bg_F4F6F8` : `bd_ccc bd_hover_1 bg_fff bg_hover_F4F6F8`">
                                                            <a @click="__cart_position(`pay_method_card_credit`);">
                                                                <div class="p15 pl20 pr20 flexx flex_ac">
                                                                    <div class="w20 mr10">
                                                                        <div v-if="OBJ.CART?.pay?.method == 'card_credit'" class="w16 h16 bdw5 bd_1 bg_fff br50p"></div>
                                                                        <div v-else class="w16 h16 flexx flex_c flex_ac bd_ccc br50p"></div>
                                                                    </div>
                                                                    <div class="fz13">Cartão de Crédito</div>
                                                                </div>
                                                                <div class="mt--4 pb15 ml25 flexx_x flex_ac gap_4">
                                                                    <img :src="`${DIR_P()}/img/default/pay/card-visa.svg`" class="h22 bd_ccc br5">
                                                                    <img :src="`${DIR_P()}/img/default/pay/card-mastercard.svg`" class="h22 bd_ccc br5">
                                                                    <img :src="`${DIR_P()}/img/default/pay/card-amex.svg`" class="h22 bd_ccc br5">
                                                                    <img :src="`${DIR_P()}/img/default/pay/card-aura.svg`" class="h22 bd_ccc br5">
                                                                    <img :src="`${DIR_P()}/img/default/pay/card-diners.svg`" class="h22 bd_ccc br5">
                                                                    <img :src="`${DIR_P()}/img/default/pay/card-discover.svg`" class="h22 bd_ccc br5">
                                                                    <img :src="`${DIR_P()}/img/default/pay/card-elo.svg`" class="h22 bd_ccc br5">
                                                                    <img :src="`${DIR_P()}/img/default/pay/card-hipercard.svg`" class="h22 bd_ccc br5">
                                                                    <img :src="`${DIR_P()}/img/default/pay/card-jcb.svg`" class="h22 bd_ccc br5">
                                                                </div>
                                                            </a>
                                                            <!-- BOXS -->
                                                                <div v-if="OBJ.CART?.pay?.method == `card_credit`" class="p20 pt0 font_Inter">
                                                                    <!-- NEW -->
                                                                        <div v-if="SHOW.cards_new_edit==true || OBJ.cards?.length==0" class="">
                                                                            <div v-if="OBJ.cards?.length!=0"><a @click="SHOW.cards_new_edit = false" class="pb20 fz13"><i class="pr5 faa-angle-left"></i> Voltar</a></div>

                                                                            <!-- CARD -->
                                                                                <div class="posr w250 h150 m-a __CARD__" :class="`${SHOW.card_verse ? `verse` : ``} __CARD__${FORM.v.card_brand}`">
                                                                                    <div class="posa t0 l0 w100p h100p bg_ccc br10 flipper" >
                                                                                        <!-- FRONT -->
                                                                                            <div class="posa t0 l0 w100p h100p __CARD_FRONT__">
                                                                                                <div class="posr pl20 pr20">
                                                                                                    <div class="posa t0 r0 mt10 mr10">
                                                                                                        <img v-if="FORM.v.card_brand == `visa`" :src="`${DIR_P()}/img/default/pay/card-visa.svg`" class="h35 br5">
                                                                                                        <img v-if="FORM.v.card_brand == `mastercard`" :src="`${DIR_P()}/img/default/pay/card-mastercard.svg`" class="h35 br5">
                                                                                                        <img v-if="FORM.v.card_brand == `amex`" :src="`${DIR_P()}/img/default/pay/card-amex.svg`" class="h35 br5">
                                                                                                        <img v-if="FORM.v.card_brand == `aura`" :src="`${DIR_P()}/img/default/pay/card-aura.svg`" class="h35 br5">
                                                                                                        <img v-if="FORM.v.card_brand == `diners`" :src="`${DIR_P()}/img/default/pay/card-diners.svg`" class="h35 br5">
                                                                                                        <img v-if="FORM.v.card_brand == `discover`" :src="`${DIR_P()}/img/default/pay/card-discover.svg`" class="h35 br5">
                                                                                                        <img v-if="FORM.v.card_brand == `elo`" :src="`${DIR_P()}/img/default/pay/card-elo.svg`" class="h35 br5">
                                                                                                        <img v-if="FORM.v.card_brand == `hipercard`" :src="`${DIR_P()}/img/default/pay/card-hipercard.svg`" class="h35 br5">
                                                                                                        <img v-if="FORM.v.card_brand == `jcb`" :src="`${DIR_P()}/img/default/pay/card-jcb.svg`" class="h35 br5">
                                                                                                    </div>

                                                                                                    <div class="posa t0 l0 w40 h28 mt30 ml20 bg_B8B8B8 br5"><div class="w28 h18 mt5 bg_D9D9D9 brr5"></div></div>
                                                                                                    <div class="fz16 c_fff" style="padding-top: 70px;">{{ FORM.v.card_number ? FORM.v.card_number : `•••• •••• •••• ••••` }}</div>
                                                                                                    <div class="pt20 fz12 c_fff">
                                                                                                        <div class="w135 fll pt5 limit">{{ FORM.v.name ? upper(FORM.v.name) : `NOME E SOBRENOME` }}</div>
                                                                                                        <div class="w75 fll pl10 flexx">
                                                                                                            <div class="w20 fll pt8 fz6 tar"><div class="h8">vali</div><div class="">dade</div></div>
                                                                                                            <div class="w40 fll pl8">
                                                                                                                <div class="fz6">MÊS/ANO</div>
                                                                                                                <div class="w50" :class="(FORM.v?.card_validate && FORM.v.card_validate.length>=6) ? `fz11` : ``">{{ FORM.v.card_validate ? FORM.v.card_validate : `••/••` }}</div>
                                                                                                            </div>
                                                                                                            <div class="clear"></div>
                                                                                                        </div>
                                                                                                        <div class="clear"></div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        <!-- FRONT -->

                                                                                        <!-- VERSE -->
                                                                                            <div class="posa t0 l0 w100p h100p __CARD_VERSE__">
                                                                                                <div class="posr">
                                                                                                    <div class="h15"></div>
                                                                                                    <div class="h30 bg_383838"></div>
                                                                                                    <div class="pt15 pl10 pr10">
                                                                                                        <div class="w185 fll h25 bg_fff"></div>
                                                                                                        <div class="w45 fll pt4 pl10 c_fff">{{ FORM.v.card_cvv ? FORM.v.card_cvv : `•••` }}</div>
                                                                                                        <div class="clear"></div>
                                                                                                    </div>
                                                                                                    <div class="pt20">
                                                                                                        <div class="w40 fll h28  ml10 bg_B8B8B8 br5"><div class="w28 h18 mt5 bg_D9D9D9 brr5"></div></div>
                                                                                                        <div class="w180 fll pl10 fz6 c_fff" style="line-height: 9px">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</div>
                                                                                                        <div class="clear"></div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        <!-- VERSE -->
                                                                                    </div>
                                                                                </div>
                                                                            <!-- CARD -->

                                                                            <form @submit.prevent="__submit_cards()">
                                                                                <div class="pt20">
                                                                                    <span class="pb5 flexx">Número do cartão</span>
                                                                                    <TEXT__ name="card_number" mask="card_number" :input="() => card_brand()" required />
                                                                                </div>

                                                                                <div class="pt20">
                                                                                    <div class="wr6">
                                                                                        <span class="pb5 flexx">Validade <span class="pl5 c_777">(mês/ano)</span> </span>
                                                                                        <TEXT__ name="card_validate" mask="card_validate" required />
                                                                                    </div>
                                                                                    <div class="wr6 pl20">
                                                                                        <span class="pb5 flexx">Cód. de segurança <span class="w15 h15 pr1 ml5 fz11 flexx flex_c flex_ac c_fff bg_999 br50p" tooltip="3 dígitos no verso do cartão. Amex: 4 dígitos na frente."><i class="faa-question"></i></span> </span>
                                                                                        <TEXT__ name="card_cvv" mask="card_cvv" :focus="() => SHOW.card_verse = true" :blur="() => SHOW.card_verse = false" required />
                                                                                    </div>
                                                                                    <div class="clear"></div>
                                                                                </div>

                                                                                <div class="pt20">
                                                                                    <span class="pb5 flexx">Nome e sobrenome do Titular</span>
                                                                                    <TEXT__ name="name" :value="OBJ?.cards_edit?.name" required />
                                                                                </div>

                                                                                <div class="pt20">
                                                                                    <span class="pb5 flexx">CPF ou CNPJ do titular</span>
                                                                                    <TEXT__ name="cpf_cnpj" :value="OBJ?.cards_edit?.cpf_cnpj" required />
                                                                                </div>

                                                                                <div class="pt20">
                                                                                    <span class="pb5 flexx">Data do nascimento do titular</span>
                                                                                    <TEXT__ type="date" name="birth" :value="OBJ?.cards_edit?.birth ?? OBJ.user.birth" required />
                                                                                </div>

                                                                                <div class="pt20">
                                                                                    <span class="pb5 flexx">Telefone do titular</span>
                                                                                    <TEXT__ name="phone" :value="OBJ?.cards_edit?.phone ?? OBJ.user.phone" required />
                                                                                </div>

                                                                                <div class="pt20">
                                                                                    <span class="pb5 flexx">Email do titular</span>
                                                                                    <TEXT__ name="email" :value="OBJ?.cards_edit?.email ?? OBJ.user.email" required />
                                                                                </div>

                                                                                <div class="pt20">
                                                                                    <span class="fz13 fwb5">Endereço do titular</span>
                                                                                    <label class="pt10 flexx flex_ac">
                                                                                        <input type="checkbox" v-model="FORM.v.address_shipping" @input="__cards_address_shipping" />
                                                                                        <span class="pl5">Usar mesmo endereço de entrega</span>
                                                                                    </label>
                                                                                </div>

                                                                                <div v-show="!FORM.v.address_shipping">
                                                                                    <div class="wr6 pt10">
                                                                                        <span class="pb5 flexx">CEP</span>
                                                                                        <TEXT__ name="zipcode" mask="zipcode" :value="OBJ?.cards_edit?.zipcode" placeholder="00.000-000" tags="change" required />
                                                                                    </div>
                                                                                    <div class="clear"></div>

                                                                                    <div v-show="FORM.v.city">
                                                                                        <div class="pt20 fz14">{{ FORM.v.city }} / {{ FORM.v.uf }}</div>

                                                                                        <div class="pt20">
                                                                                            <span class="pb5 flexx">Endereço</span>
                                                                                            <TEXT__ name="address" :value="OBJ?.cards_edit?.address" required />
                                                                                        </div>
                                                                                        <div class="pt20">
                                                                                            <div class="wr4">
                                                                                                <span class="pb5 flexx">Número</span>
                                                                                                <TEXT__ name="number" :value="OBJ?.cards_edit?.number" required />
                                                                                            </div>
                                                                                            <div class="wr8  pl20">
                                                                                                <span class="pb5 flexx">Bairro</span>
                                                                                                <TEXT__ name="neighborhood" :value="OBJ?.cards_edit?.neighborhood" required />
                                                                                            </div>
                                                                                            <div class="clear"></div>
                                                                                        </div>
                                                                                        <div class="pt20">
                                                                                            <div class="pb5">Complemento: <span class="c_777">(opional)</span></div>
                                                                                            <TEXT__ name="complement" :value="OBJ?.cards_edit?.complement" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="pt20">
                                                                                    <button type="submit" class="w100p h40 fz15i bd0i br15 button_cart_1" >Salvar</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    <!-- NEW -->


                                                                    <!-- LIST -->
                                                                        <div v-else>
                                                                            <div class=""><a @click="__cards_new()" class="p5 pl10 pr10 fz13 bd_333 bg_hover_eee br5">+ Novo cartão</a></div>
                                                                            <div class="pt10">
                                                                                <div v-for="(value, key) in OBJ?.cards" class="mb10 br5" :class="value.id == OBJ.CART?.cards?.id ? `bd_1 bg_e5e5e5` : `bd_ccc bd_hover_1 bg_fff bg_hover_e5e5e5`">
                                                                                    <div class="p0 pl20 pr20 flexx flex_j flex_ac">
                                                                                        <a @click="__cart_position(`cards`, value)" class="w20 pt10 pb10 mr10">
                                                                                            <div v-if="value.id == OBJ.CART?.cards?.id" class="w16 h16 bdw5 bd_1 bg_fff br50p"></div>
                                                                                            <div v-else class="w16 h16 flexx flex_c flex_ac bd_ccc br50p"></div>
                                                                                        </a>
                                                                                        <a @click="__cart_position(`cards`, value)" class="pt10 pb10">
                                                                                            <img v-if="value.brands == `visa`" :src="`${DIR_P()}/img/default/pay/card-visa.svg`" class="h22 bd_ccc br5">
                                                                                            <img v-if="value.brands == `mastercard`" :src="`${DIR_P()}/img/default/pay/card-mastercard.svg`" class="h22 bd_ccc br5">
                                                                                            <img v-if="value.brands == `master`" :src="`${DIR_P()}/img/default/pay/card-master.svg`" class="h22 bd_ccc br5">
                                                                                            <img v-if="value.brands == `amex`" :src="`${DIR_P()}/img/default/pay/card-amex.svg`" class="h22 bd_ccc br5">
                                                                                            <img v-if="value.brands == `aura`" :src="`${DIR_P()}/img/default/pay/card-aura.svg`" class="h22 bd_ccc br5">
                                                                                            <img v-if="value.brands == `diners`" :src="`${DIR_P()}/img/default/pay/card-diners.svg`" class="h22 bd_ccc br5">
                                                                                            <img v-if="value.brands == `discover`" :src="`${DIR_P()}/img/default/pay/card-discover.svg`" class="h22 bd_ccc br5">
                                                                                            <img v-if="value.brands == `elo`" :src="`${DIR_P()}/img/default/pay/card-elo.svg`" class="h22 bd_ccc br5">
                                                                                            <img v-if="value.brands == `hipercard`" :src="`${DIR_P()}/img/default/pay/card-hipercard.svg`" class="h22 bd_ccc br5">
                                                                                            <img v-if="value.brands == `jcb`" :src="`${DIR_P()}/img/default/pay/card-jcb.svg`" class="h22 bd_ccc br5">
                                                                                        </a>
                                                                                        <a @click="__cart_position(`cards`, value)" class="flex_1 pt10 pb10 pl10">
                                                                                            <div class="limit">{{ value.name }}</div>
                                                                                            <div class="pt2">•••• {{ value.last_four }}</div>
                                                                                        </a>
                                                                                        <div class="pt10 pb10 tac">
                                                                                            <a @click="__cards_delete(value)" class="dib p5 pb7 fz18 vam c_red"><i class="faa-times"></i></a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div v-if="OBJ.cards?.length==0" class="pt10 pb10 fz13">Nenhum cartão encontrado...</div>
                                                                            </div>

                                                                            <div v-if="OBJ.CART?.cards?.id" class="pt10">
                                                                                <div class="">
                                                                                    <span class="pb5 flexx">Cód. de segurança <span class="w15 h15 pr1 ml5 fz11 flexx flex_c flex_ac c_fff bg_999 br50p" tooltip="3 dígitos no verso do cartão. Amex: 4 dígitos na frente."><i class="faa-question"></i></span> </span>
                                                                                    <TEXT__ name="card_cvv" mask="card_cvv" />
                                                                                </div>
                                                                                <div class="pt20">
                                                                                    <span class="pb5 flexx">Parcelas </span>
                                                                                    <SELECT__ name="card_installments" mask="card_installments" class="designx" no_sel="1" value="1" :options="OBJ.installments" :change="() => { __cart_position(`card_installments`); }" />
                                                                                </div>
                                                                                <div class="pt20 pb10"><a @click="__pay()" class="w100p h45 fz15i flexx flex_c flex_ac bd0i b_degrade_green br15 button_cart_1" ><i class="pr10 faa-lock"></i> Comprar Agora</a></div>
                                                                            </div>
                                                                        </div>
                                                                    <!-- LIST -->
                                                                </div>
                                                            <!-- BOXS -->
                                                        </div>
                                                    <!-- CARD_CREDIT -->


                                                    <!-- PIX -->
                                                        <div v-if="count(OBJ.CART?.pay?.pix)" class="mb10 br5" :class="OBJ.CART?.pay?.method == 'pix' ? `bd_1 bg_F4F6F8` : `bd_ccc bd_hover_1 bg_fff bg_hover_F4F6F8`">
                                                            <a @click="__cart_position(`pay_method_pix`);" class="p15 pl20 pr20 flexx flex_ac">
                                                                <div class="w20 mr10">
                                                                    <div v-if="OBJ.CART?.pay?.method == 'pix'" class="w16 h16 bdw5 bd_1 bg_fff br50p"></div>
                                                                    <div v-else class="w16 h16 flexx flex_c flex_ac bd_ccc br50p"></div>
                                                                </div>
                                                                <div class="fz13">Pix</div>
                                                                <div class="pl10 mb--5"><img src="@/vendor/assets/img/svg/pay_pix.svg" class="h18" /></div>
                                                            </a>
                                                            <!-- BOXS -->
                                                                <div v-if="OBJ.CART?.pay?.method == `pix`" class="p20 pt0">
                                                                    <div class="">A confirmação de pagamento é realizada em poucos minutos. Utilize o aplicativo do seu banco para pagar.</div>
                                                                    <div class="pt20 fz17 fwb6 c_green">Valor no Pix: R$&nbsp;{{ OBJ.CART?.total ? OBJ.CART.total : `0,00` }}</div>
                                                                    <div class="pt20 pb10"><a @click="__pay()" class="w100p h45 fz15i flexx flex_c flex_ac bd0i b_degrade_green br15 button_cart_1" ><i class="pr10 faa-lock"></i> Comprar Agora</a></div>
                                                                </div>
                                                            <!-- BOXS -->
                                                        </div>
                                                    <!-- PIX -->


                                                    <!-- BOLETO -->
                                                        <div v-if="count(OBJ.CART?.pay?.boleto)" class="mb10 br5" :class="OBJ.CART?.pay?.method == 'boleto' ? `bd_1 bg_F4F6F8` : `bd_ccc bd_hover_1 bg_fff bg_hover_F4F6F8`">
                                                            <a @click="__cart_position(`pay_method_boleto`);" class="p15 pl20 pr20 flexx flex_ac">
                                                                <div class="w20 mr10">
                                                                    <div v-if="OBJ.CART?.pay?.method == 'boleto'" class="w16 h16 bdw5 bd_1 bg_fff br50p"></div>
                                                                    <div v-else class="w16 h16 flexx flex_c flex_ac bd_ccc br50p"></div>
                                                                </div>
                                                                <div class="fz13">Boleto</div>
                                                                <div class="pl10 mb--5"><img src="@/vendor/assets/img/svg/pay_boleto.svg" class="h24" /></div>
                                                            </a>
                                                            <!-- BOXS -->
                                                                <div v-if="OBJ.CART?.pay?.method == `boleto`" class="p20 pt0">
                                                                    <div class="">Pague com o boleto bancário, pode pagar em qualquer banco.</div>
                                                                    <div class="pt20 fz17 fwb6 c_green">Valor no Boleto: R$&nbsp;{{ OBJ.CART?.total ? OBJ.CART.total : `0,00` }}</div>
                                                                    <div class="pt20 pb10"><a @click="__pay()" class="w100p h45 fz15i flexx flex_c flex_ac bd0i b_degrade_green br15 button_cart_1" ><i class="pr10 faa-lock"></i> Comprar Agora</a></div>
                                                                </div>
                                                            <!-- BOXS -->
                                                        </div>
                                                    <!-- BOLETO -->
                                                </div>
                                            </div>

                                        </div>
                                    <!-- PAY -->
                                </div>
                                <div class="clear"></div>
                            </div>
                        <!-- ACTIONS -->                        





                        <!-- PRODUCTS -->
                            <!-- DESK -->
                                <Z_CART_SUMARY__ v-if="!MOBILE()" />
                            <!-- DESK -->
                        <!-- PRODUCTS -->
                    </div>
                <!-- CART -->










                <!-- SUCCESS -->
                    <div v-else-if="OBJ?.success && SHOW.GET[2] == `success`" class="">
                        <div v-if="OBJ?.success?.id">
                            <div class="max-w800 p40 m-a fz16 tac bdb_E2E8F0 bg_fff br10">

                                <!-- CARD_CREDIT -->
                                    <div v-if="OBJ?.success?.pay_method == `card_credit`" class="pb20">
                                        <div class="tac"><img src="@/vendor/assets/img/svg/default/check.svg" class="dib" /></div>
                                        <div class="pt20 fz20 fwb6 tac c_green">Pagamento efetuado com sucesso!</div>

                                        <div class="pt20">
                                            <div class="fz13 fwb5">Valor</div>
                                            <div class="pt5 fz20 fwb6 tac">{{ price(OBJ.success?.total) }} <span class="fz14">({{ OBJ.success?.installments }}x)</span></div>
                                        </div>

                                        <div class="pt30 fz14 lh24 fwb5 tac">Seu pagamento foi efetuado e esta em analise pela operadora, em segundos já você receberá um e-mail com o status do pagamento.</div>
                                    </div>
                                <!-- CARD_CREDIT -->


                                <!-- PIX -->
                                    <div v-if="OBJ?.success?.pay_method == `pix`" class="pb20">
                                        <div class="tac"><img src="@/vendor/assets/img/svg/default/check.svg" class="dib" /></div>
                                        <div class="pt20 fz20 fwb6 tac">Pagar com Pix</div>

                                        <div class="pt20">
                                            <div class="fz13 fwb5">Valor</div>
                                            <div class="pt5 fz20 fwb6 tac">{{ price(OBJ.success?.total) }}</div>
                                        </div>

										<div class="pt25 tac">
                                            <iframe :src="`${DIR_API()}/qrcode/${base64_encode(OBJ.success.gateway_pix_qrcode)}/250`" class="dib" width="250" height="250" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></iframe>
										</div>
										<div class="pt30">
											<div class="fz13 fwb6 tac">Copiar código Pix</div>
											<div class="posr pt10">
												<input class="w100p p10 pr50 h55 fz14 fwb4 bd_ccc back_fff br5 coppy" :value="OBJ.success.gateway_pix_qrcode" readonly />
												<a @click="copy__(`input.coppy`)" class="posa t0 r0 pt25 pb20 pl15 pr15"><img src="@/vendor/assets/img/svg/default/copy.svg" class="w24" /></a>
											</div>
										</div>

                                        <div v-if="OBJ.success?.gateway_pix_qrcode_url" class="pt20">
                                             <a :href="OBJ.success.gateway_pix_qrcode_url" class="pt15 pb15 flexx flex_c flex_ac button_3" target="_blank"><img src="@/vendor/assets/img/svg/default/download.svg" /> <div class="pl10">Fazer download do QRCode</div></a>
                                        </div>
                                    </div>
                                <!-- PIX -->


                                <!-- BOLETO -->
                                    <div v-if="OBJ?.success?.pay_method == `boleto`" class="">
                                        <div class="tac"><img src="@/vendor/assets/img/svg/default/check.svg" class="dib" /></div>
                                        <div class="pt20 fz20 fwb6 tac">Seu boleto foi gerado com sucesso!</div>

                                        <div class="pt20">
                                            <div class="fz13 fwb5">Valor</div>
                                            <div class="pt5 fz20 fwb6 tac">{{ price(OBJ.success?.total) }}</div>
                                        </div>

										<div class="pt25 tac">
											<div class="fz13 fwb5">Data de Vencimento</div>
											<div class="pt5 fz17 fwb6">
												<div>{{ date__(OBJ.success.gateway_boleto_expiration) }}</div>
											</div>
										</div>
										<div class="pt30">
											<div class="fz13 fwb6 tac">Copiar código de barras</div>
											<div class="posr pt10">
												<input class="w100p p10 pr50 h55 fz14 fwb4 bd_ccc back_fff br5 coppy" :value="OBJ.success.gateway_boleto_barcode" readonly />
												<a @click="copy__(`input.coppy`)" class="posa t0 r0 pt25 pb20 pl15 pr15"><img src="@/vendor/assets/img/svg/default/copy.svg" class="w24" /></a>
											</div>
										</div>

                                        <div v-if="OBJ.success?.gateway_boleto_url" class="pt20">
                                             <a :href="OBJ.success.gateway_boleto_url" class="pt15 pb15 flexx flex_c flex_ac button_3" :class="MOBILE() ? `fz14i` : `fz16i`" target="_blank"><img src="@/vendor/assets/img/svg/default/download.svg" /> <div class="pl10">Fazer download do boleto</div></a>
                                        </div>
                                    </div>
                                <!-- BOLETO -->

                            </div>
                        </div>
                        <div v-else class="p40 fz16 tac bdb_E2E8F0 bg_fff br10">Pedido não encontrado!</div>
                    </div>
                <!-- SUCCESS -->










                <!-- REJECTED -->
                    <div v-else-if="OBJ?.rejected && SHOW.GET[2] == `rejected`" class="">
                        <div v-if="OBJ?.rejected?.id">
                            <div class="max-w800 p40 m-a fz16 tac bdb_E2E8F0 bg_fff br10">

                                <!-- CARD_CREDIT -->
                                    <div v-if="OBJ?.rejected?.pay_method == `card_credit`" class="pb20">
                                        <div class="tac"><i class="fz70 c_red faa-times-circle"></i></div>
                                        <div class="pt20 fz20 fwb6 tac c_red">Pagamento Recusado!</div>

                                        <div class="pt20">
                                            <div class="fz13 fwb5">Valor</div>
                                            <div class="pt5 fz20 fwb6 tac">{{ price(OBJ.rejected?.total) }} <span class="fz14">({{ OBJ.rejected?.installments }}x)</span></div>
                                        </div>

                                        <div class="pt30 fz14 lh24 fwb5 tac">Não foi possível aprovar o seu pagamento. Verifique os dados do cartão ou tente novamente com outro cartão ou com ou método de pagamento.</div>

                                        <div class="pt40">
                                             <a @click="open__(`/cart`, {}, 1)" class="max-w300 pt15 pb15 m-a flexx flex_c flex_ac button_3" :class="MOBILE() ? `fz14i` : `fz16i`">Tentar novamente</a>
                                        </div>
                                    </div>
                                <!-- CARD_CREDIT -->

                            </div>
                        </div>
                    </div>
                <!-- REJECTED -->









                <!-- CART VAZIO -->
                    <div v-else class="p40 bdb_E2E8F0 bg_fff br10">
                        <div class="pt40 pb40 tac">
                            <div class="fz26 fwb6 tac">Carrinho Vazio!</div>
                            <div class="pt20 fz14">Nenhum produto foi adicionado no carrinho...</div>

                            <div class="dib pt40 tac">
                                <a @click="open__(`/products`, {}, 1)" aria-label="Carrinho Pagamento" class="button_cart_1 p10 pl40 pr40"> <span class="dib vam">COMPRAR PRODUTOS</span> </a>
                            </div>
                        </div>
                    </div>
                <!-- CART VAZIO -->

            </div>
        </div>

    </div>

</template>


<style>
    .__DASHBOARD__.__PG__cart__ header { display: none !important; }
    .__DASHBOARD__.__PG__cart__ .__BORDER_RADIUS_LEFT__,
    .__DASHBOARD__.__PG__cart__ .__BORDER_RADIUS_RIGHT__ { display: none !important; }
</style>

<style scoped>
    .__CARD__ .flipper { transition: transform 1s; transform-style: preserve-3d; }
    .__CARD__.verse .flipper { transform: rotateY(180deg); }
    .__CARD__ .__CARD_FRONT__ { backface-visibility: hidden; }
    .__CARD__ .__CARD_VERSE__ { backface-visibility: hidden; transform: rotateY(180deg); }
</style>
