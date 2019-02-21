<template>
  <div :class="['group relative flex flex-row items-center border-b border-grey-light py-2 mb-2', { deleting: deleting }]">
    <div :class="['rounded-full border mr-4', checked ? '' : 'p-3 shadow-inner']"
         role="checkbox"
         @click="changeStatus"
    >
      <span v-show="checked" class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="rounded-full stroke-current text-white fill-current bg-blue-dark w-6 h-6">
        <path class="secondary" d="M10 14.59l6.3-6.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-3-3a1 1 0 0 1 1.4-1.42l2.3 2.3z"></path>
      </svg>
      </span>
    </div>

    <textarea-autosize
      v-model.trim="title"
      :class="['todo w-3/4 md:w-4/5 max-w-full text-lg text-grey-darker bg-transparent focus:outline-none focus:text-black', checked ? 'line-through text-grey' : '']"
      :min-height="20"
      @blur.native="updateName"
    >
    </textarea-autosize>

    <div class="absolute pin-r block md:hidden group-hover:block p-1 cursor-pointer text-grey hover:text-red bg-transparent"
         @click="deleteTodo"
    >
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="stroke-current fill-current w-4">
        <path class="primary" d="M5 5h14l-.89 15.12a2 2 0 0 1-2 1.88H7.9a2 2 0 0 1-2-1.88L5 5zm5 5a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1z"></path>
        <path class="secondary" d="M8.59 4l1.7-1.7A1 1 0 0 1 11 2h2a1 1 0 0 1 .7.3L15.42 4H19a1 1 0 0 1 0 2H5a1 1 0 1 1 0-2h3.59z"></path>
      </svg>
    </div>
  </div>
</template>

<script>
  /*global axios*/
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
        title: this.todo.title,
        checked: this.todo.complete,
        deleting: false,
      }
    },
    
    methods: {
      changeStatus() {
        this.checked = !this.checked;
        axios.patch(`/app/todo/${this.todo.id}/status`)
          .then(() => {
            this.$emit('changeStatus', this.index);
          })
          .catch(() => {
            this.checked = !this.checked;
            console.warn('Cannot update tasks at this time')
          });

      },

      updateName() {
        if (this.title === this.todo.title) return;

        axios.patch(`/app/todo/${this.todo.id}/title`, {
          title: this.title,
        })
          .then(() => {
            this.$emit('changeName', this.index, this.title);
          })
          .catch(() => {
            this.title = this.todo.title;
            console.warn('Cannot update title at this time..')
          });
      },

      deleteTodo() {
        this.deleting = true;
        axios.delete(`/app/todo/${this.todo.id}`)
          .then(() => {
            this.$emit('deleteTodo', this.index)
          })
          .catch(() => {
            this.deleting = false;
            console.warn('Cannot delete to-dos at this time..')
          })
      },
    },
  }
</script>

<style scoped>
  .deleting {
    animation: fade-out .4s linear forwards;
  }

    @keyframes fade-out {
      from { opacity: 1; }
      to { opacity: 0; display: none; }
    }
</style>
