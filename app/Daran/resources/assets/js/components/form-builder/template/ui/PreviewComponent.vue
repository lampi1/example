<template>
    <div class="modal" id="previewModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h3>Anteprima</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body" v-if="formData !== null">
                    <form-builder-gui ref="FormBuilderGui" :form="formData" :key="formData._uniqueId"></form-builder-gui>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-primary" @click="getData">Get Data</button>-->
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Chiudi</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import FormBuilderGui from '../../gui/FormBuilderGui';

    export default {
        name: "PreviewComponent",
        components: {FormBuilderGui},
        props: ['form'],
        data: () => ({
            previewModal: null,
            formData: null
        }),
        methods: {
            openModal(formData) {
                // set data
                this.formData = _.cloneDeep(formData);
                this.formData._uniqueId = Math.random();

                // open
                this.previewModal.modal('show');
            },
            closeModal() {
                this.previewModal.modal('hide');
            },
            getData() {
                console.log(this.$refs.FormBuilderGui.getValue());
            }
        },
        mounted() {
            this.previewModal = $(this.$el);
        }
    }
</script>

<style scoped>

</style>
