 <div x-data="{
        isOpen: false,

        toggle() {
            this.isOpen = !this.isOpen;
        },

        close() {
            this.isOpen = false;
        },
    }"
    class="relative">
    <!-- Slot trigger - start -->
    <div @click="toggle" tabindex="0" class="group cursor-pointer">
        {{ $trigger }}
    </div>
    <!-- Slot trigger - end -->

     <!-- Slot content - start -->
     <div @click.outside="close" x-show="isOpen" x-transition class="absolute right-0 mt-2 w-40 rounded-lg p-4 bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 ring-1 ring-slate-900/5 shadow-xl">
        {{ $content }}
     </div>
     <!-- Slot content - end -->
 </div>
