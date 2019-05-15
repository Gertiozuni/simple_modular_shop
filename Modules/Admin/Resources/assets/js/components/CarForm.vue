<template>
    <div>
        <form class="needs-validation" @submit.prevent="submit" @keydown="form.errors.clear($event.target.title)">
            <div class="mb-3">
                <label for="title">Title</label>
                <input type="text" :class="{ 'is-invalid': form.errors.has('title'), 'form-control': true}" v-model="form.title" id="title" placeholder="e.x Fiat Punto">
                <div class="invalid-feedback" v-text="form.errors.get('title')"></div>
            </div>

            <div class="mb-3">
                <label for="description">Description</label>
                <textarea type="text" :class="{ 'is-invalid': form.errors.has('description'), 'form-control': true}" v-model="form.description" id="description" rows="6" placeholder="Description"></textarea>
                <div class="invalid-feedback" v-text="form.errors.get('description')"></div>
            </div>

            <hr class="mb-3">

            <div class="row">
                <div class="col-md-6 mb-3" v-for="(attr, name) in form.attributes">
                    <label :for="name">{{ titleCase(name) }}</label>
                    <input :type="name === 'price' ? 'number' : 'text'" :class="{ 'is-invalid': form.errors.has(`attributes.${name}`), 'form-control': true}" v-model="form.attributes[name]" :id="name" :placeholder="titleCase(name)">
                    <div class="invalid-feedback" v-text="form.errors.get(`attributes.${name}`)"></div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tags">Tags</label>
                    <vue-tags-input
                            id="tags"
                            v-model="form.tag"
                            :tags="form.tags"
                            @tags-changed="newTags => form.tags = newTags"/>
                </div>
            </div>

            <button class="btn btn-primary btn-lg btn-block" type="submit">Add new Car</button>
        </form>
    </div>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';
    import Form from '../helpers/Form';

    export default {
        components: {
            VueTagsInput,
        },
        props: ['car'],
        data() {
            return {
                form: new Form({
                    title: '',
                    description: '',
                    tag: '',
                    attributes: {
                        make : '',
                        model: '',
                        registration: '',
                        engine_size: '',
                        price: ''
                    },
                    tags: []
                })
            }
        },
        computed: {
            attributeGroup() {
                return Array.from(Array(Math.ceil(Object.values(this.attributes).length / 2)).keys())
            }
        },
        methods: {
            titleCase(attr) {
                return attr.replace(
                    /\w\S*/g,
                    function(txt) {
                        let text = txt.split('_').join(' ');
                        return text.charAt(0).toUpperCase() + text.substr(1).toLowerCase();
                    }
                )
            },
            submit() {
                this.form.post('/admin/cars')
                    .then(response => window.location.href = '/admin/cars');
            }
        }
    }
</script>