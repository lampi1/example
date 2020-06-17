<template>
    <div>
        <div class="row" v-if="control.type !== 'checkbox'">
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Valore Predefinito</label>
                <input class="w-100" type="text" v-model="control.defaultValue">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Etichetta</label>
                <input class="w-100" type="text" v-model="control.label">
            </div>
                <div class="col-12 mb-2">
                    <div class="input-checkbox">
                        <input class="input-checkbox__hidden" id="f-bold" type="checkbox" v-model="control.labelBold" hidden/>
                        <label class="input-checkbox__icon" for="f-bold">
                            <span>
                                <svg width="12px" height="10px" viewbox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                </svg>
                            </span>
                        </label>
                        <p class="input-checkbox__text">
                            Grassetto
                        </p>
                    </div>
                </div>
                <div class="col-12 mb-2">
                    <div class="input-checkbox">
                        <input class="input-checkbox__hidden" id="f-italic" type="checkbox" v-model="control.labelItalic" hidden/>
                        <label class="input-checkbox__icon" for="f-italic">
                            <span>
                                <svg width="12px" height="10px" viewbox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                </svg>
                            </span>
                        </label>
                        <p class="input-checkbox__text">
                            Italico
                        </p>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-checkbox">
                        <input class="input-checkbox__hidden" id="f-underline" type="checkbox" v-model="control.labelUnderline" hidden/>
                        <label class="input-checkbox__icon" for="f-underline">
                            <span>
                                <svg width="12px" height="10px" viewbox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                </svg>
                            </span>
                        </label>
                        <p class="input-checkbox__text">
                            Sottolineato
                        </p>
                    </div>
                </div>

                <div class="col-12 mt-3 mb-3">
                    <label class="control-label mb-1">Immagine</label>
                    <input type="file" class="form-control" ref="componentImage" accept="image/*" @change="loadImage()">
                </div>

                <div class="col-12 mt-3 mb-3" v-if="control.image">
                    <img :src="control.image" alt="" width="100%">
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "BaseStyleComponent",
        props: {
            control: {
                type: Object
            },
        },
        methods: {
            loadImage(){
                var file = this.$refs.componentImage.files[0];
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    this.control.image = e.target.result;
                    this.control.labelUnderline = true;
                };
            }
        }
    }
</script>

<style scoped>

</style>
