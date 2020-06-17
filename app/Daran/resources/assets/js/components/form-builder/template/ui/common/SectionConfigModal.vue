<template>
    <div>
        <div class="modal" id="sectionConfigModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content form-template-1">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h3 class="modal-title">Configurazione Sezione</h3>
                        <button type="button" class="close" data-dismiss="modal">
                            <img v-bind:src="'/images/icons/close-black.svg'" alt="close">
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" v-if="section !== null">
                        <div class="mb-3">
                            <label class="control-label mb-1">Chiave univoca sezione</label>
                            <input type="text" class="form-control" readonly v-model="section.clientKey">
                        </div>
                        <div class="mb-3">
                            <label class="control-label mb-1">Posizione Etichetta</label>
                            <select class="v-select" v-model="section.labelPosition">
                                <option value="left">Orizzontale</option>
                                <option value="top">Verticale</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="input-checkbox">
                                <input class="input-checkbox__hidden" id="dynamic" v-model="section.isDynamic"  type="checkbox" hidden/>
                                <label class="input-checkbox__icon" for="dynamic">
                                    <span>
                                        <svg width="12px" height="10px" viewbox="0 0 12 10">
                                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                        </svg>
                                    </span>
                                </label>
                                <p class="input-checkbox__text">
                                    Sezione dinamica?
                                </p>
                            </div>
                        </div>
                        <div class="row" v-if="section.isDynamic">
                            <div class="col-6">
                                <label class="control-label mb-1">Min ripetizioni</label>
                                <input type="number"
                                       min="0"
                                       class="form-control"
                                       data-toggle='tooltip'
                                       title="Minimum instance for dynamic section"
                                       v-model="section.minInstance">
                            </div>
                            <div class="col-6">
                                <label class="control-label mb-1">Max ripetizioni</label>
                                <input type="number"
                                       min="0"
                                       class="form-control"
                                       data-toggle='tooltip'
                                       title="Maximum instance for dynamic section. 0 for unlimited."
                                       v-model="section.maxInstance">
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="save">Salva</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">Chiudi</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const SECTION_ID = "#sectionConfigModal";

    export default {
        name: "SectionConfigModal",
        props:['updateSectionInfo'],
        data: () => ({
            index: null,
            section: null
        }),
        methods: {
            openModal(sectionInfo, index) {
                this.section = _.cloneDeep(sectionInfo);
                this.index = _.clone(index);
                $(SECTION_ID).modal('show');
            },
            closeModal() {
                $(SECTION_ID).modal('hide');
            },
            save() {
                if (_.isEmpty(this.section.clientKey)) {
                    this.section.clientKey = this.section.name;
                }
                // format data
                this.section.minInstance = parseInt(this.section.minInstance);
                this.section.maxInstance = parseInt(this.section.maxInstance);

                this.$emit('updateSectionInfo', this.section, this.index);
                this.closeModal();
            },
        },
        mounted() {
            $("[data-toggle='tooltip']").tooltip();
        }
    }
</script>

<style scoped>

</style>
