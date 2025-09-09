import { open__, open_href } from "@/vendor/services/events";


    // NEW REQUEST
        export function NEW__request(): boolean
        {

            // HOME -> DASHBOARD
                if($_GET[0] != `admin` && $_GET[0] != `dashboard`){
                    if($_GET['PG'] === `home`){
                        open__(`/dashboard/`);
                        return true;
                    }
                }
                if($_GET[0] == `dashboard`){
                    if($_GET['PG'] === `home`){
                        open__(`/dashboard/customers/`);
                        return true;
                    }
                }
            // HOME -> DASHBOARD

            return false;
        }
    // NEW REQUEST
