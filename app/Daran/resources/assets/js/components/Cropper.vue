<template>
    <div>
        <tui-image-editor :include-ui="useDefaultUI" :options="options"></tui-image-editor>
    </div>
</template>
        <script type="text/javascript" src="black-theme.js"></script>
<script>
import {ImageEditor} from '@toast-ui/vue-image-editor';


export default {
    components: {
        'tui-image-editor': ImageEditor
    },
    data() {
        return {
            useDefaultUI: 1,
            options: { // for options prop
                cssMaxWidth: 700,
                cssMaxHeight: 500,
                 usageStatistics: false
            }
        }
    }
}
</script>
