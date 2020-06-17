<template>
  <div class="editor">
      <ImageModal ref="imgmodal" @onConfirm="addCommand" />

    <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
      <div class="menubar">

        <button type="button"
          class="menubar__button"
          :class="{ 'is-active': isActive.bold() }"
          @click="commands.bold"
          data-icon="b" title="Grassetto" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
          :class="{ 'is-active': isActive.italic() }"
          @click="commands.italic"
          data-icon="j" title="Corsivo" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
          :class="{ 'is-active': isActive.strike() }"
          @click="commands.strike"
          data-icon="a" title="Barrato" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button mr-2"
          :class="{ 'is-active': isActive.underline() }"
          @click="commands.underline"
          data-icon="k" title="Sottolineato" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
          :class="{ 'is-active': isActive.heading({ level: 1 }) }"
          @click="commands.heading({ level: 1 })"
          data-icon="f" title="H1" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
          :class="{ 'is-active': isActive.heading({ level: 2 }) }"
          @click="commands.heading({ level: 2 })"
          data-icon="g" title="H2" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button mr-2"
          :class="{ 'is-active': isActive.heading({ level: 3 }) }"
          @click="commands.heading({ level: 3 })"
          data-icon="h" title="H3" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
          :class="{ 'is-active': isActive.bullet_list() }"
          @click="commands.bullet_list"
          data-icon="e" title="Lista" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
          :class="{ 'is-active': isActive.ordered_list() }"
          @click="commands.ordered_list"
          data-icon="d" title="Lista numerata" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button mr-2"
          @click="commands.horizontal_rule"
          data-icon="c" title="Linea divisoria" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
        @click="commands.alignment({ textAlign: 'left' })"
        data-icon="5" title="Allinea a sinistra" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
        @click="commands.alignment({ textAlign: 'center' })"
        data-icon="3" title="Allinea al centro" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button mr-2"
        @click="commands.alignment({ textAlign: 'right' })"
        data-icon="4" title="Allinea a destra" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button mr-2"
         @click="openModal(commands.image)"
          data-icon="2" title="Immagine" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
          @click="commands.undo"
          data-icon="r" title="Annulla" data-tooltip="tooltip"
        >
        </button>

        <button type="button"
          class="menubar__button"
          @click="commands.redo"
          data-icon="q" title="Ripristina" data-tooltip="tooltip"
        >
        </button>

      </div>
    </editor-menu-bar>
    <editor-content class="editor__content" :editor="editor"/>
    <input type="text" name="content" :value="html" hidden>
  </div>
</template>

<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
 import Paragraph from './paragraph.js';
 import ImageModal from "./ImageModal";

import {
  Blockquote,
  CodeBlock,
  HardBreak,
  Heading,
  HorizontalRule,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History,
  Image,
} from 'tiptap-extensions'
export default {
  components: {
    EditorContent,
    EditorMenuBar,
    ImageModal
  },
  data() {
    return {
      editor: new Editor({
        extensions: [
          new Blockquote(),
          new BulletList(),
          new CodeBlock(),
          new HardBreak(),
          new Heading({ levels: [1, 2, 3] }),
          new HorizontalRule(),
          new ListItem(),
          new OrderedList(),
          new TodoItem(),
          new TodoList(),
          new Link(),
          new Bold(),
          new Code(),
          new Italic(),
          new Strike(),
          new Underline(),
          new History(),
          new Image(),
          new Paragraph(),
        ],
        content: '',
        onUpdate: ({ getJSON, getHTML }) => {
          this.json = getJSON()
          this.html = getHTML()
        }
      }),
      json: '',
      html: '',
    }
  },
  methods: {
      openModal(command) {
          this.$refs.imgmodal.showModal(command);
        },
        addCommand(data) {
          if (data.command !== null) {
            data.command(data.data);
          }
      }
  },
  beforeMount: function () {
      this.html = document.getElementById('form').getAttribute('data-content');
      this.editor.setContent(this.html);
  },
  mounted: function() {
        tippy('[data-tooltip="tooltip"]', {
          content(reference) {
            const title = reference.getAttribute('title')
            reference.removeAttribute('title')
            return title
          },
          arrow:true
        });
    },
  beforeDestroy() {
    this.editor.destroy()
  },
}
</script>
