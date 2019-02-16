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
              class="toggle-dropdown text-sm text-grey-lightest no-underline hover:no-underline hover:text-grey focus:text-grey p-3"
              @click="toggleDropdown"
            >
              {{ user.name }} <span class="text-xs">
                &#x25BC;
              </span>
            </a>

            <div
              v-show="dropdownIsOpen"
              class="dropdown-menu absolute pin-t pin-r mt-8 bg-white rounded shadow-lg"
            >
              <ul class="list-reset px-2 py-4">
                <li>
                  <a
                    href="javascript:void(0)"
                    class="text-grey-darkest hover:bg-grey-light rounded px-4 py-2 no-underline"
                    @click="logout"
                  >
                    Logout
                  </a>
                  <form
                    id="logout-form"
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

    created() {
      document.getElementsByTagName('html')[0].addEventListener('click', (e) => {
        if (!e.target.classList.contains('dropdown-menu') && !e.target.classList.contains('toggle-dropdown')) {
          this.dropdownIsOpen = false;
        }
      })
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
