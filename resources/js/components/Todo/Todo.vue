<template>
  <div class="flex flex-col mt-0 md:mt-24">
    <div class="w-1/2 mx-auto h-16 mb-2">
      <input
        v-model="todo"
        type="text"
        class="appearance-none w-full text-xl text-grey-darker bg-transparent border-b-2 border-grey focus:outline-none py-2 mb-1"
        placeholder="Buy groceries"
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
      <div class="w-full">
        <todo-item
          v-for="(item, index) in todos"
          :key="item.id"
          :todo="item"
          :index="index"
          @changeStatus="changeStatus(index)"
          @changeName="changeName"
        />
      </div>
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
        todo: null,
        todos: [],
        showHelperText: false,
        id: 1,
      }
    },

    created() {
      // this.fetchToDos();
    },

    methods: {
      fetchToDos() {
        axios('/app/todo')
          .then(response => {
            this.todos.incomplete = response.data.incomplete;
            this.todos.complete = response.data.complete;
          })
          .catch(() => {
            console.warn('Cannot get to-dos at this time...')
          })
      },
      
      addToDo(e) {
        if (this.todo !== null && this.todo.length > 0) {

          // Make axios request

          this.todos.unshift({
            id: this.id++,
            todo: e.target.value,
            isComplete: false
          });
          this.todo = '';
        }
      },

      findInArray(id) {
        return this.todos.findIndex(function(element) {
          return element.id === id;
        });
      },

      changeStatus(index) {
        // Make axios request

        this.todos[index].isComplete = !this.todos[index].isComplete;

      },

      changeName(index, newName) {
        this.todos[index].todo = newName;
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
