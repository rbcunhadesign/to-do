<template>
  <nav class="bg-blue-darkest shadow mb-8 py-6">
    <div class="container mx-auto px-6 md:px-0">
      <div class="flex items-center justify-center">
        <div class="mr-6">
          <a
            href="#"
            class="text-lg font-semibold text-white no-underline"
          >
            To-Do
          </a>
        </div>
        <div class="flex-1 text-right">
          <div class="relative">
            <a
              href="javascript:void(0)"
              class="text-sm text-grey-lightest no-underline hover:no-underline hover:text-grey focus:text-grey p-3"
              @click="toggleDropdown"
            >
              {{ user.name }}
            </a>

            <div
              v-show="dropdownIsOpen"
              class="absolute pin-t pin-r mt-8 bg-white rounded shadow-lg"
            >
              <ul class="list-reset p-2 py-4">
                <li class="mb-4">
                  <a
                    href="#"
                    class="text-grey-darkest hover:bg-grey-light rounded p-2 no-underline"
                  >
                    Link 1
                  </a>
                </li>
                <li class="mb-3">
                  <a
                    href="#"
                    class="text-grey-darkest hover:bg-grey-light rounded p-2 no-underline"
                  >
                    Profile
                  </a>
                </li>
                <li class="mb-3">
                  <hr class="border-b-2 border-grey-dark">
                </li>
                <li>
                  <a
                    href="javascript:void(0)"
                    class="text-grey-darkest hover:bg-grey-light rounded p-2 no-underline"
                    @click="logout"
                  >
                    Logout
                  </a>
                  <form
                    id="logout"
                    method="POST"
                    action="/logout"
                    class="hidden"
                  >
                    <input
                      type="hidden"
                      name="_token"
                      :value="token"
                    >
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
  export default {
    name: "TopNav",

    props: {
      user: {
        type: Object,
        required: true,
      },
    },

    data() {
      return {
        token: document.head.querySelector('meta[name="csrf-token"]').content,
        dropdownIsOpen: false,
      }
    },

    methods: {
      toggleDropdown() {
        this.dropdownIsOpen = !this.dropdownIsOpen;
      },

      logout() {
        document.getElementById('logout-form').submit();
      }
    },
  }
</script>
