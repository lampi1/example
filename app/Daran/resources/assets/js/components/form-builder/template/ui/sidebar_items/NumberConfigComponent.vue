<template>
    <!-- Decimal places for number -->
    <div class="row">
        <div class="col-12 mb-3">
            <div class="input-checkbox">
                <input class="input-checkbox__hidden" name="isInteger" id="isInteger" type="checkbox" v-model="control.isInteger" hidden/>
                <label class="input-checkbox__icon" for="isInteger">
                    <span>
                        <svg width="12px" height="10px" viewbox="0 0 12 10">
                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                        </svg>
                    </span>
                </label>
                <p class="input-checkbox__text">
                    Solo Interi
                </p>
            </div>
        </div>
        <div class="col-12 mb-3">
            <label class="control-label mb-1">Posizioni decimali</label>
                <input type="number" min="0" max="5" class="form-control decimalPlaces"
                       v-model="control.decimalPlace" :disabled="control.isInteger">
        </div>
    </div>
</template>

<script>
    export default {
        name: "NumberConfigComponent",
        props: {
            control: {
                type: Object
            },
        },
    }
</script>

<style scoped>

</style>
