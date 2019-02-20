<template>
  <div class="flex flex-col mt-0 md:mt-24">
    <div class="w-1/2 mx-auto h-16 mb-2">
      <input
        v-model="todo"
        type="text"
        class="appearance-none w-full text-xl text-grey-darker bg-transparent border-b-2 border-grey focus:outline-none py-2 mb-1"
        placeholder="Add new task"
        @focus="showHelperText = true"
        @blur="showHelperText = false"
        @keyup.enter="addToDo"
      >
      <transition name="fade">
        <span
          v-show="showHelperText"
          class="text-xs text-grey"
        >
          Press enter key when done
        </span>
      </transition>
    </div>

    <div class="w-1/2 mx-auto">
      <div v-if="todos.length > 0 && !loading" class="w-full">
        <todo-item
          v-for="(item, index) in todos"
          :key="item.id"
          :todo="item"
          :index="index"
          @changeStatus="changeStatus(index)"
          @changeName="changeName"
        />
      </div>

      <h3 v-else-if="todos.length < 1 && !loading" class="text-center text-grey mt-4">
        Good job your list is empty!
      </h3>
    </div>
  </div>
</template>

<script>
  /*global axios*/
  import TodoItem from "./TodoItem";

  export default {
    name: "Todo",
    components: {TodoItem},
    data() {
      return {
        loading: true,
        todo: null,
        todos: [],
        showHelperText: false,
        id: 1,
      }
    },

    created() {
      this.fetchToDos();
    },

    methods: {
      fetchToDos() {
        axios('/app/todo')
          .then(response => {
            this.todos = response.data.data;
            this.loading = false;
          })
          .catch(() => {
            console.warn('Cannot get to-dos at this time...')
          })
      },
      
      addToDo() {
        if (this.todo !== null && this.todo.length > 0) {

          axios.post('/app/todo', {
            title: this.todo,
          })
            .then(response => {
              this.todos.unshift({
                id: response.data.next_id - 1,
                title: this.todo,
                complete: false
              });
              this.todo = '';
            })
              .catch(() => {
                console.warn('Cannot create to-dos at this time')
              });
        }
      },

      findInArray(id) {
        return this.todos.findIndex(function(element) {
          return element.id === id;
        });
      },

      changeStatus(index) {
        this.todos[index].complete = this.todos[index].complete ? 0 : 1;
      },

      changeName(index, newName) {
        this.todos[index].title = newName;
      },
    }
  }
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .25s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
</style>
