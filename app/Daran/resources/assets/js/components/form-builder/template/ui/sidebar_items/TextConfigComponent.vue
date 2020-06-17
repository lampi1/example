<template>
    <div>
        <label>
            <div class="input-checkbox mb-3">
                <input class="input-checkbox__hidden" id="m-line" type="checkbox" v-model="control.isMultiLine" hidden/>
                <label class="input-checkbox__icon" for="m-line">
                    <span>
                        <svg width="12px" height="10px" viewbox="0 0 12 10">
                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                        </svg>
                    </span>
                </label>
                <p class="input-checkbox__text">
                    Multi-linea?
                </p>
            </div>
        </label>
    </div>
</template>

<script>
    export default {
        name: "TextConfigComponent",
        props: {
            control: {
                type: Object
            },
        },
    }
</script>

<style scoped>

</style>
