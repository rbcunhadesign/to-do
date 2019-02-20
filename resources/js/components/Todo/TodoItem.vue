<template>
  <div class="flex flex-row items-center border-b border-grey-light py-2 mb-2">
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
    <input
      v-model="title"
      type="text"
      :class="['todo w-full text-lg text-grey-darker bg-transparent focus:outline-none', checked ? 'line-through text-grey' : '']"
      @blur="updateName"
    >
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
    },
  }
</script>
