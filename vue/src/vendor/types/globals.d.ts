export {};

declare global {
    // APP
        var $_APP: number;
    // APP


    // GET
        var $_GET: {
            [key: string]: any;
        }
    // GET


    // GLOBAL
        var $_GLOBAL: {
            DIR: string;
            DIR_API: string;
            HEX_1: string;
            HEX_2: string;
            HEX_3: string;
            HEX_4: string;
            HEX_5: string;
            HEX_6: string;
            HEX_7: string;
            HEX_8: string;
            HEX_9: string;
            HEX_10: string;

            HEX_COLOR: string;
            HEX_BUTTON_COLOR: string;
            HEX_BUTTON_BD: string;
            HEX_BUTTON_BDW: string;
            HEX_BUTTON_BG: string;

            SHOW: any;
            FORM: any;
            OBJ: any;
            INTERVAL: any;

            ROUTE: any;
            ROUTER: any;
            $TNS?: any;

            city__: any;
            uf__: any;
            city__value: any;
            zipcode__: any;
            address_ok: any;

            x: number;
        };
        
        // Alias para compatibilidade
        var $GLOBAL: typeof $_GLOBAL;
    // GLOBAL


    // PAY
        interface Window {
            MercadoPago: {
                tokenize?: (data: Record<string, unknown>, callback: (status: number, response: unknown) => void) => void;
                createCardToken?: (data: Record<string, unknown>, callback: (status: number, response: unknown) => void) => void;
                setPublishableKey?: (key: string) => void;
                [key: string]: unknown;
            };
        }
        // interface Window {
        //     MercadoPago: any;
        // }
    // PAY


    // CAROUSEL
        interface CarouselOptions {
            container?: string | Element;
            items?: number;
            slideBy?: number | 'page';
            speed?: number;
            autoplay?: boolean;
            autoplayTimeout?: number;
            controls?: boolean;
            nav?: boolean;
            loop?: boolean;
            responsive?: Record<number, { items: number; slideBy?: number }>;
            [key: string]: unknown;
        }
        function tns(options: CarouselOptions): any;
        // function tns(options: any): any;
    // CAROUSEL
}