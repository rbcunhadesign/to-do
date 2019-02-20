<template>
  <div class="flex flex-col mt-0 md:mt-24">
    <div class="w-3/4 mx-auto h-16">
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

    <div v-if="todos.length > 0 && !loading" class="flex w-3/4 mx-auto mt-4 mb-6">
      <span class="text-xs text-red-lighter cursor-pointer hover:text-red"
            @click="deleteComplete"
      >
        Remove completed tasks
      </span>

      <span class="text-xs text-blue-light cursor-pointer ml-auto hover:text-blue"
            @click="toggleComplete"
      >
        {{ showCompleteOnly ? 'Show' : 'Hide' }} complete
      </span>
    </div>

    <div class="w-3/4 mx-auto">
      <div v-if="todos.length > 0 && !loading" class="w-full">
        <todo-item
          v-for="(item, index) in todos"
          :key="item.id"
          :todo="item"
          :index="index"
          @changeStatus="changeStatus(index)"
          @changeName="changeName"
          @deleteTodo="deleteTodo"
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
        showCompleteOnly: false,
        next_id: null,
      }
    },

    created() {
      this.fetchToDos();
    },

    methods: {
      fetchToDos(completeOnly = false) {
        let base = '/app/todo';
        let url = completeOnly ? base + '?complete' : base;

        axios(url)
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

          if (this.next_id === null) {
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
                this.next_id = response.data.next_id;
              })
              .catch(() => {
                console.warn('Cannot create to-dos at this time')
              });
          } else {
            let title = this.todo;
            this.todos.unshift({
              id: this.next_id++,
              title: this.todo,
              complete: false
            });
            this.todo = '';

            axios.post('/app/todo', {
              title: title,
            })
              .then(response => {

              })
              .catch(() => {
                this.todos.splice(0, 1);
                this.todo = title;
                this.next_id--;
                console.warn('Cannot create to-dos at this time')
              });
          }
        }
      },

      changeStatus(index) {
        if (this.showCompleteOnly) {
          setTimeout(() => {
            this.todos.splice(index, 1);
          }, 500)
        } else {
          this.todos[index].complete = this.todos[index].complete ? 0 : 1;
        }
      },

      changeName(index, newName) {
        this.todos[index].title = newName;
      },

      toggleComplete() {
        this.showCompleteOnly = !this.showCompleteOnly;
        this.fetchToDos(this.showCompleteOnly)
      },

      deleteTodo(index) {
        setTimeout(() => {
          this.todos.splice(index, 1);
        }, 200);
      },

      deleteComplete() {
        axios.delete('/app/todo')
          .then(() => {
            this.fetchToDos(this.showCompleteOnly)
          })
          .catch(() => {
            console.warn('Cannot delete to-dos at this time..')
          })
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
