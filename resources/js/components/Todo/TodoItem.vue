<template>
  <div class="flex flex-row items-center border-b border-grey-light py-2 mb-2">
    <label
      for="changeStatus"
      class="relative checkbox-container"
    >
      <input
        id="changeStatus"
        v-model="isComplete"
        type="checkbox"
        class="checkbox appearance-none bg-transparent border rounded-full shadow-inner p-3 mr-4 focus:outline-none"
        :title="'Mark as ' + isComplete ? 'incomplete' : 'complete'"
      >
      <span class="checkmark" />
    </label>
    <input
      v-model="title"
      type="text"
      :class="['todo w-full text-lg text-grey-darker bg-transparent focus:outline-none', isComplete ? 'line-through text-grey' : '']"
      @blur="updateName"
    >
  </div>
</template>

<script>
  export default {
    name: "TodoItem",
    props: {
      todo: {
        type: Object,
        required: true,
      },
      index: {
        type: Number,
        required: true,
      }
    },
    
    data() {
      return {
        title: this.todo.todo,
        isComplete: this.todo.isComplete,
      }
    },

    watch: {
      'isComplete': 'changeStatus',
    },
    
    methods: {
      changeStatus() {
        this.$emit('changeStatus');
      },

      updateName() {

        // Make axios request

        this.$emit('changeName', this.index, this.title);
      },
    },
  }
</script>

<style scoped>
  .checkbox,
  .todo {
    transition: all .5s linear
  }

    .checkbox:checked {
        background-color: #3490dc;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        /*display: none;*/
        opacity: 0;
        transition: all .5s linear;
    }

    .checkbox-container input:checked ~ .checkmark:after {
        display: block;
        opacity: 1;
    }

    .checkbox-container .checkmark:after {
        left: 11px;
        top: 7px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
</style>