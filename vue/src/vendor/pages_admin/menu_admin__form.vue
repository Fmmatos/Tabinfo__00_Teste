<script setup lang="ts">
import { onBeforeMount, inject } from 'vue';

    // INJECT
        const SHOW = inject<any>(`SHOW`);
        const FORM = inject<any>(`FORM`);
        const OBJ = inject<any>(`OBJ`);
    // INJECT

    // PROPS
        const PROPS = defineProps<{
            n__: string | number;
        }>();
    // PROPS

    // SHOW
    // SHOW

    // FORM
    // FORM

    // OBJ
    // OBJ

    // EVENTS
    // ONBEFOREMOUNT
            onBeforeMount(() => {
                FORM.v.input[PROPS.n__] = FORM.v.input[PROPS.n__] ? FORM.v.input[PROPS.n__] : {};
                FORM.v.input[PROPS.n__] = Array.isArray(FORM.v.input[PROPS.n__]) ? {} : FORM.v.input[PROPS.n__];

                if (FORM.v?.input?.[PROPS.n__].type == undefined){
                    FORM.v.input[PROPS.n__].type = `text`;
                }

                if (FORM.v?.input?.[PROPS.n__].wr == undefined){
                    FORM.v.input[PROPS.n__].wr = `wr6`;
                }

                if (FORM.v?.input?.[PROPS.n__].align == undefined){
                    FORM.v.input[PROPS.n__].align = `left`;
                }

                // RESET SELECT NO EXISTS
                    // let $zerar = 1;
                    // if (FORM.v.input[PROPS.n__].options){
                    //     for(let key in OBJ.QUERY?.menu_admin){
                    //         let value = OBJ.QUERY?.menu_admin[key];

                    //         if (FORM.v.input[PROPS.n__].options == value.id){
                    //             $zerar = 0;
                    //         }
                    //     }
                    // }
                    // if ($zerar){
                    //     FORM.v.input[PROPS.n__].options = null;
                    // }
                // RESET SELECT NO EXISTS
            });
        // ONBEFOREMOUNT
    // EVENTS

    // FUNCTIONS
        const __type = () => {
            FORM.v.input[PROPS.n__].check = true;
            if (FORM.v.input[PROPS.n__].type == `categories`){
                FORM.v.input[PROPS.n__].label = `Categorias`;
                FORM.v.input[PROPS.n__].name = `categories`;
                FORM.v.input[PROPS.n__].tags = `required`;
            }
            if (FORM.v.input[PROPS.n__].type == `subcategories`){
                FORM.v.input[PROPS.n__].label = `Categorias`;
                FORM.v.input[PROPS.n__].name = `subcategories`;
                FORM.v.input[PROPS.n__].tags = `required`;
            }

            if (FORM.v.input[PROPS.n__].type == `password`){
                FORM.v.input[PROPS.n__].label = `Senha`;
                FORM.v.input[PROPS.n__].name = `password`;
                FORM.v.input[PROPS.n__].tags = `maxlength="8" required validate_password`;
            }

            if (FORM.v.input[PROPS.n__].type == `price`){
                FORM.v.input[PROPS.n__].label = `Valor`;
                FORM.v.input[PROPS.n__].name = `price`;
                FORM.v.input[PROPS.n__].tags = `mask="price"`;
            }

            if (FORM.v.input[PROPS.n__].type == `zipcode`){
                FORM.v.input[PROPS.n__].label = `Cep`;
                FORM.v.input[PROPS.n__].name = `zipcode`;
                FORM.v.input[PROPS.n__].tags = `change mask="zipcode" required`;
                FORM.v.input[PROPS.n__].extra = `|->icon->search`;
            }
            if (FORM.v.input[PROPS.n__].type == `address`){
                FORM.v.input[PROPS.n__].label = `Endereço`;
                FORM.v.input[PROPS.n__].name = `address`;
                FORM.v.input[PROPS.n__].tags = `required`;
            }
            if (FORM.v.input[PROPS.n__].type == `address_number`){
                FORM.v.input[PROPS.n__].label = `Número`;
                FORM.v.input[PROPS.n__].name = `number`;
                FORM.v.input[PROPS.n__].tags = `required`;
            }
            if (FORM.v.input[PROPS.n__].type == `address_complement`){
                FORM.v.input[PROPS.n__].label = `Complemento`;
                FORM.v.input[PROPS.n__].name = `complement`;
                FORM.v.input[PROPS.n__].tags = ``;
            }
            if (FORM.v.input[PROPS.n__].type == `address_neighborhood`){
                FORM.v.input[PROPS.n__].label = `Bairro`;
                FORM.v.input[PROPS.n__].name = `neighborhood`;
                FORM.v.input[PROPS.n__].tags = `required`;
            }
            if (FORM.v.input[PROPS.n__].type == `uf`|| FORM.v.input[PROPS.n__].type == `uf_text`){
                FORM.v.input[PROPS.n__].label = `Estados`;
                FORM.v.input[PROPS.n__].name = FORM.v.input[PROPS.n__].type;
                FORM.v.input[PROPS.n__].tags = `required`;
            }
            if (FORM.v.input[PROPS.n__].type == `city` || FORM.v.input[PROPS.n__].type == `city_text`){
                FORM.v.input[PROPS.n__].label = `Cidades`;
                FORM.v.input[PROPS.n__].name = FORM.v.input[PROPS.n__].type;
                FORM.v.input[PROPS.n__].tags = `required`;
            }
        }
    // FUNCTIONS
</script>


<template>
    <div class="">
        <!-- CHECK -->
            <div class="w85 fll pt10 flexx flex_ac draggable_move" draggable="true">
                <div class="w20 mr5"><em class="db w20 h20 c-p bg_ccc br2" style="cursor: grab;"></em></div>
                <label class="c-p flexx flex_ac">
                    <div class=""><input type="checkbox" name="check" v-model="FORM.v.input[PROPS.n__].check" value="1" class="design"></div>
                    <div class="w35 pl5 limit">
                        ({{ 
                            PROPS.n__ == SHOW.linhas_max ? `TXT` 
                            : (PROPS.n__ == (SHOW.linhas_max + 1) ? `EDT` 
                                : (isNaN(Number(PROPS.n__)) ? PROPS.n__ : Number(PROPS.n__) + 1))
                        }})
                    </div>
                </label>
            </div>
        <!-- CHECK -->

        <!-- TYPE -->
            <div class="posr w80 fll no_sel">
                <select name="type" v-model="FORM.v.input[PROPS.n__].type" class="design" @change="__type()">
                    <option value="text">Text</option>
                    <option value="categories">Categories</option>
                    <option value="subcategories">Subcategories</option>
                    <option value="price">Price</option>
                    <option value="password">Password</option>
                    <option value="email">Email</option>
                    <option value="date">Date</option>
                    <option value="datetime-local">Date e Hour</option>
                    <option value="month">Date (Month / Year)</option>

                    <option value="column">Column</option>
                    <option value="json">Json</option>
                    <option value="json_fields">Json Fields</option>

                    <option value="color">Color</option>
                    <option value="number">Number</option>
                    <option value="range">Range</option>
                    <option value="url">Url</option>
                    <option value="tel">Phone</option>
                    <option value="file">File</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="radio">Radio</option>
                    <option value="select">Select</option>
                    <option value="textarea">Textarea</option>
                    <option value="editor">Editor</option>
                    <option value="hidden">Hidden</option>
                    <option value="info">Info</option>

                    <option value="zipcode">Address Zipcode</option>
                    <option value="address">Address</option>
                    <option value="address_number">Address Number</option>
                    <option value="address_complement">Address Complement</option>
                    <option value="address_neighborhood">Address Neighborhood</option>
                    <option value="uf">Address UF</option>
                    <option value="city">Address City</option>
                    <option value="city_text">Address City (Text)</option>
                    <option value="uf_text">Address UF (Text)</option>
                </select>
            </div>
        <!-- TYPE -->

        <!-- TITLE -->
            <div class="posr w90 fll ml5">
                <input type="text" name="title" v-model="FORM.v.input[PROPS.n__].title" class="design dynamic_width_200" placeholder="Title">
            </div>
        <!-- TITLE -->

        <!-- WR -->
            <div class="posr w80 fll ml5">
                <select name="wr" v-model="FORM.v.input[PROPS.n__].wr" class="design">
                    <option value="wr1">WR1</option>
                    <option value="wr15">WR1,5</option>
                    <option value="wr2">WR2</option>
                    <option value="wr25">WR2,5</option>
                    <option value="wr3">WR3</option>
                    <option value="wr35">WR3,5</option>
                    <option value="wr4">WR4</option>
                    <option value="wr45">WR4,5</option>
                    <option value="wr5">WR5</option>
                    <option value="wr55">WR5,5</option>
                    <option value="wr6">WR6</option>
                    <option value="wr65">WR6,5</option>
                    <option value="wr7">WR7</option>
                    <option value="wr75">WR7,5</option>
                    <option value="wr8">WR8</option>
                    <option value="wr85">WR8,5</option>
                    <option value="wr9">WR9</option>
                    <option value="wr95">WR9,5</option>
                    <option value="wr10">WR10</option>
                    <option value="wr105">WR10,5</option>
                    <option value="wr11">WR11</option>
                    <option value="wr115">WR11,5</option>
                    <option value="wr12">WR12</option>
                </select>
            </div>
        <!-- WR -->

        <!-- ALIGN -->
            <div class="posr w90 fll ml5">
                <select name="align" v-model="FORM.v.input[PROPS.n__].align" class="designx">
                    <option value="left">Left</option>
                    <option value="right">Right</option>
                </select>
            </div>
        <!-- ALIGN -->

        <!-- TEXT -->
            <!-- LABEL -->
                <div class="posr w125 fll pl5 no_sel">
                    <input type="text" name="label" v-model="FORM.v.input[PROPS.n__].label" class="design dynamic_width_200">
                </div>
            <!-- LABEL -->

            <!-- NAME -->
                <div class="posr w125 fll pl5 no_sel">
                    <input type="text" name="name" v-model="FORM.v.input[PROPS.n__].name" class="design dynamic_width_200">
                </div>
            <!-- NAME -->

            <!-- TAGS -->
                <div class="posr w200 fll pl5 no_sel">
                    <div class=""><input type="text" name="tags" v-model="FORM.v.input[PROPS.n__].tags" class="design dynamic_width_300"></div>
                </div>
            <!-- TAGS -->

            <!-- OPTIONS -->
                <div class="posr w130 fll pl5 no_sel">
                    <select name="options" v-model="FORM.v.input[PROPS.n__].options" class="design" style="max-width: 150px">
                        <option value="">- - - -</option>
                        <slot v-for="(value, key) in OBJ?.QUERY?.menu_admin_all" :key="key">
                            <option v-if="value.type != 1" :value="value.id">{{ value.name }} (#{{ value.id }}) ({{ value.type==2 ? `D` : `M` }})</option>
                        </slot>
                    </select>
                </div>
            <!-- OPTIONS -->

            <!-- EXTRA -->
                <div class="posr w300 fll pl5 no_sel">
                    <input type="text" name="extra" v-model="FORM.v.input[PROPS.n__].extra" class="design dynamic_width_500" placeholder="Extra">
                </div>
            <!-- EXTRA -->

            <!-- CREATE_COLUMN -->
                <div class="posr w90 fll pl5 no_sel">
                    <select name="create_column" v-model="FORM.v.input[PROPS.n__].create_column" class="designx">
                        <option value=""> - - - </option>
                        <option value="bigint">Bigint</option>
                        <option value="bigint_key">Bigint Key</option>
                        <option value="integer">Int</option>
                        <option value="date">Date</option>
                        <option value="datetime">Date Time</option>
                        <option value="decimal">Decimal</option>
                        <option value="varchar_50">Varc (50)</option>
                        <option value="varchar">Varc (255)</option>
                        <option value="text">Text</option>
                        <option value="text_long">longText</option>
                        <option value="json">Json</option>
                    </select>
                </div>
            <!-- CREATE_COLUMN -->

            <!-- ORDER -->
                <div class="posr w50 fll pl5 no_sel">
                    <input type="text" name="order" v-model="FORM.v.input[PROPS.n__].order" class="design tac" :tabindex="Number(PROPS.n__) + 1">
                </div>
            <!-- ORDER -->

            <!-- SEARCH -->
                <div class="posr w75 fll pl5 no_sel">
                    <div class="w125 flexx gap_5">
                        <div class="w75">
                            <select name="search" v-model="FORM.v.input[PROPS.n__].search" class="designx">
                                <option value=""> - - - </option>
                                <option value="top">Top</option>
                            </select>
                        </div>

                        <div class="w50">
                            <input type="text" name="search_order" v-model="FORM.v.input[PROPS.n__].search_order" class="design tac">
                        </div>
                    </div>                
                </div>
            <!-- SEARCH -->

            <div class="clear"></div>
        <!-- TEXT -->
    </div>

</template>


<style>
    .dynamic_width_200,
    .dynamic_width_300,
    .dynamic_width_400,
    .dynamic_width_500 { width: auto; transition: width 0.3s ease; }

    .dynamic_width_200:focus { position: relative; z-index: 1; width: 200px !important; }
    .dynamic_width_300:focus { position: relative; z-index: 1; width: 300px !important; }
    .dynamic_width_400:focus { position: relative; z-index: 1; width: 400px !important; }
    .dynamic_width_500:focus { position: relative; z-index: 1; width: 500px !important; }
</style>