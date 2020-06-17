<template>
    <div class="row" v-if="form !== null">
        <!-- collapse layout -->
        <div class="col-12 sectionItem" v-if="form.layout === 'collapse'">
            <div class="row">
                <collapse-section-layout v-for="(section, index) in form.sections"
                                         :key="section.name"
                                         :section="section">
                </collapse-section-layout>
            </div>
        </div>

        <!-- fixed layout -->
        <div class="col-12 sectionItem" v-if="form.layout === 'fixed'">
            <div class="row">
                <fixed-section-layout v-for="(section, index) in form.sections"
                                         :key="section.name"
                                         :section="section">
                </fixed-section-layout>
            </div>
        </div>

        <!-- tab layout-->
        <div class="col-12 sectionItem" v-if="form.layout === 'tab'">
            <div class="row">
                <tab-header-section-layout :sections="form.sections"></tab-header-section-layout>

                <div class="tab-content">
                    <tab-body-section-layout v-for="(section, index) in form.sections"
                                             :key="section.name"
                                             :section="section"
                                             :is-first="index === 0">
                    </tab-body-section-layout>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import TabHeaderSectionLayout from "./sections/TabHeaderSectionLayout";
    import TabBodySectionLayout from "./sections/TabBodySectionLayout";
    import CollapseSectionLayout from "./sections/CollapseSectionLayout";
    import FixedSectionLayout from "./sections/FixedSectionLayout";
    export default {
        name: "SectionComponent",
        components: {CollapseSectionLayout, FixedSectionLayout, TabBodySectionLayout, TabHeaderSectionLayout},
        props: {
            form: {
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>
