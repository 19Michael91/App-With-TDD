<template>
    <modal name="new-project" classes="p-10 bg-card rounded-lg" height="auto">
        <h1 class="font-normal mb-16 text-center text-2x1">Let's Start Something New</h1>
        <form v-on:submit.prevent="submit">
            <div class="flex">
                <div class="flex-1 mr-4">
                    <div class="mb-4">
                        <label for="title" class="text-sm">Title</label>
                        <input type="text"
                               id="title"
                               class="border mt-2 p-2 text-xs w-full rounded"
                               :class="form.errors.title ? 'border-error' : 'border-muted-light'"
                               v-model="form.title">
                        <span class="text-xs italic text-red-600" v-if="form.errors.title" v-text="form.errors.title[0]"></span>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="text-sm">Description</label>
                        <textarea id="description"
                                  class="border mt-2 p-2 text-xs w-full rounded"
                                  :class="form.errors.description ? 'border-error' : 'border-muted-light'"
                                  rows="7"
                                  v-model="form.description"></textarea>
                        <span class="text-xs italic text-red-600" v-if="form.errors.description" v-text="form.errors.description[0]"></span>
                    </div>
                </div>
                <div class="flex-1 ml-4">
                    <div class="mb-4">
                        <label class="text-sm">Need Some Tasks?</label>
                        <input type="text"
                               class="border border-muted-light m-2 p-2 text-xs w-full rounded"
                               placeholder="Task 1"
                               v-for="task in form.tasks"
                               v-model="task.body">
                    </div>
                    <button type="button" v-on:click="addTask" class="button-gray hover:text-white focus:text-white mb-5" name="button">Add New Task</button>
                </div>
            </div>

            <footer class="flex justify-end">
                <button type="button" class="button-cancel-modal mr-3" name="cancel" v-on:click="$modal.hide('new-project')">Cancel</button>
                <button class="button" name="create">Create Project</button>
            </footer>
        </form>
    </modal>
</template>

<script>
    import BirdboardForm from './BirdboardForm';
    export default {
        data() {
            return {
                form: new BirdboardForm({
                    title: '',
                    description: '',
                    tasks: [
                        { body: ''},
                    ]
                })
            };
        },
        methods: {
            addTask() {
                this.form.tasks.push({ body: '' });
            },
            async submit() {
                if (! this.form.tasks[0].body) {
                    delete this.form.originalData.tasks;
                }
                this.form.submit('/projects')
                    .then(response => location = response.data.message);
            }
        }
    }
</script>
