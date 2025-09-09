import api from '@/vendor/services/api';
import { explode } from './events';


    // MERCADOPAGO
        export  async function MercadoPago__js(type: string = `cart`): Promise<any>
        {

            return new Promise((resolve, reject) => {
                api(`/pay/js`, { type }, async (json: any) => {
                    let $ex = explode('|', $_GLOBAL.OBJ.mercado_pago_cards.code_1);

                    let response: any = {};
                    let form = {
                        cardholderName: 		$_GLOBAL.OBJ.mercado_pago_cards.name,
                        cardNumber:				$ex[0],
                        cardExpirationMonth:	$ex[1],
                        cardExpirationYear:		$ex[2],
                        securityCode:			$_GLOBAL.FORM.v.card_cvv,
                        docType:				$_GLOBAL.OBJ.mercado_pago_cards.doc_type,
                        docNumber:				$_GLOBAL.OBJ.mercado_pago_cards.doc_number,
                    };
                    // console.log(form);

                    if ($_GLOBAL.OBJ.mercado_pago_public_key){
                        try {
                            const mp = new (window as any).MercadoPago($_GLOBAL.OBJ.mercado_pago_public_key);

                            const bin = form.cardNumber.substring(0, 6);

                            // 1. OBTER MÉTODO DE PAGAMENTO
                                const { results } = await mp.getPaymentMethods({ bin });
                                if (!results.length){
                                    // console.error(`paymentMethodId error`, `Método de pagamento não encontrado`);
                                    resolve({ 'error': `Método de pagamento não encontrado` });
                                }
                                const paymentMethod = results[0];
                                const paymentMethodId = paymentMethod?.id;
                                response.payment_method_id = paymentMethodId;
                            // 1. OBTER MÉTODO DE PAGAMENTO
                        

                            // 2. OBTER ISSUER
                                let issuerId = paymentMethod?.issuer?.id;
                                if (paymentMethod?.additional_info_needed && paymentMethod?.additional_info_needed?.includes('issuer_id')){
                                    const issuers = await mp.getIssuers({ paymentMethodId, bin });
                                    issuerId = issuers[0].id;
                                }
                                response.issuer_id = issuerId;
                            // 2. OBTER ISSUER


                            // 3. GERAR TOKEN MANUALMENTE
                                const tokenResponse = await mp.createCardToken(form);
                                const token = tokenResponse?.id;
                                response.token = token;
                            // 3. GERAR TOKEN MANUALMENTE

                            resolve(response);


                        } catch (error) {
                            console.error(`token error`, error);
                            resolve({ 'error': 1 });
                        }

                    } else {
                        console.error(`mercado_pago_public_key error`, 'no mercado_pago_public_key');
                        resolve({ 'error': 1 });
                    }
                }, 0);
            });
        }
    // MERCADOPAGO