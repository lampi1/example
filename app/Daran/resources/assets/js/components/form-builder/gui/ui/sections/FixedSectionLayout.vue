<template>
    <div class="col-12 mb-5">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-2">{{section.label}}</h4>
            </div>
            <div :id="section.name + '_gui_body'" class="col-12">
                <row-component :section="section" :key="section.name"></row-component>
            </div>
        </div>
    </div>
</template>

<script>
    import RowComponent from "../RowComponent";
    import {Hooks} from '../../components/hook_lists';

    export default {
        name: "FixedSectionLayout",
        components: {RowComponent},
        props: ['section'],
        created() {
            // before hook
            Hooks.Section.beforeRegister.run(this.section);
        },
        mounted() {
            // after hook
            Hooks.Section.afterRegister.run(this.section);
        },

    }
</script>

<style scoped>
    .fixedFormItem .title {
        font-weight: bold;
        border-bottom: 1px solid #000;
    }
</style>
