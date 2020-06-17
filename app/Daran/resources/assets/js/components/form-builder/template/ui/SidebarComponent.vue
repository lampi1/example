<template>
    <div>
        <div class="form-builder__sidebar controlSidebar list-group" v-show="!isConfig">
            <a href="#" class="list-group-item active text-uppercase">
                <b>Elementi</b>
            </a>
            <div id="sidebarControls">
                <a href="javascript:void(0)" class="form-builder__sidebar__button list-group-item list-group-item-action control-wrapper"
                   v-for="(obj, value) in controls" :data-control-type="value">
                    <font-awesome-icon :icon="obj.icon" class="pr-1"></font-awesome-icon>{{obj.label}}
                </a>
            </div>
        </div>
        <div class="settingSidebar card" v-if="isConfig">
            <div class="card-body form-template-1">
                <div class="row mb-5">
                    <div class="col-12 mb-2">
                        <p class="mb-3 fc--blue fw--semibold">{{controlInfo.label}}</p>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-primary w-100" @click="applyEditSidebar">Salva</button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-info w-100" @click="closeEditSidebar">Chiudi</button>
                    </div>
                </div>

                <base-config-component :control="controlInfo"></base-config-component>

                <component v-if="configComponent != null"
                           :is="configComponent"
                           :control="controlInfo">
                </component>

                <base-style-component :control="controlInfo"></base-style-component>
            </div>
        </div>
    </div>
</template>

<script>
    import {CONTROL_TYPES} from "../../config/control_constant";
    import {eventBus, EventHandlerConstant} from '../handler/event_handler';
    import {ControlHandler} from '../handler/control_handler';
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    import {Hooks} from '../components/hook_lists';
    import BaseConfigComponent from "./sidebar_items/BaseConfigComponent";
    import BaseStyleComponent from "./sidebar_items/BaseStyleComponent";

    export default {
        components: {BaseStyleComponent, BaseConfigComponent, FontAwesomeIcon},
        name: "sidebar-component",
        data: () => ({
            controls: CONTROL_TYPES,
            isConfig: false,
            controlInfo: null,
            configComponent: null,
        }),
        methods: {
            closeEditSidebar() {
                this.isConfig = false;
                this.controlInfo = null;
                ControlHandler.clearSelect();
            },
            applyEditSidebar() {
                if (this.controlInfo.name !== ControlHandler.getSelectedItem()) {
                    return;
                }

                // pre apply
                this.controlInfo.decimalPlace = parseInt(this.controlInfo.decimalPlace);

                // before hook here
                let b4Run = Hooks.Sidebar.beforeApplyConfig.runSequence(this.controlInfo);
                if (b4Run === false) {
                    return;
                }

                eventBus.$emit(EventHandlerConstant.ON_APPLY_EDITOR_SIDEBAR, this.controlInfo);

                // after hook here
                Hooks.Sidebar.afterApplyConfig.run(this.controlInfo);
            }
        },
        created() {
            // catch event activate sidebar here
            eventBus.$on(EventHandlerConstant.ACTIVATE_EDITOR_SIDEBAR, controlInfo => {
                // before hook here
                let b4Run = Hooks.Sidebar.beforeOpenConfig.runSequence(controlInfo);
                if (b4Run === false) {
                    return;
                }

                // open config
                this.isConfig = true;
                this.controlInfo = controlInfo;
                this.configComponent = null;

                // retrieve config component
                if (_.accessStr(this.controls[controlInfo.type], 'source.config')){
                    this.configComponent = this.controls[controlInfo.type].source.config;
                }

                // after hook here
                Hooks.Sidebar.afterOpenConfig.run(this.controlInfo);
            });
        },
        mounted() {
            // insert controls
            $(".list-group-item", $("#sidebarControls")).draggable({
                appendTo: 'body',
                containment: '',
                scroll: false,
                helper: 'clone',
                revert: true,
                zIndex: 9999,
                cancel: ".list-group-item-success",
                start: function(event, ui){
                    $(ui.helper).css('width', `${ $(event.target).width() }px`);
                }
            });

            $(this.$el).find('.controlSidebar').droppable({
                accept: ".controlItemWrapper",
                drop: function (event, ui){
                    // remove control
                    eventBus.$emit(EventHandlerConstant.REMOVE_CONTROL, ui);
                },
                over: function( event, ui ) {
                    ui.helper.css('border', '1px solid red');
                },
            });
        }
    }
</script>

<style scoped>

</style>
