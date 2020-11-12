<template>
    <div class="property-box">
        <input required @change="doUpdateValue()" v-model="name"
               :placeholder="placeholder" class="property-name-field">
        <vue-tags-input class=""
                        placeholder="Enter values here"
                        v-model="tag"
                        :tags="tags"
                        :add-on-key="[13, ',']"
                        @tags-changed="newTags => updateValue(newTags)"/>
        <span class="close-btn" @click.prevent="remove">
            <i class="material-icons">close</i>
        </span>
    </div>

</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';
    export default {
        name: "PropertyValue",
        props: ['value', 'placeholder'],
        components: {
            VueTagsInput,
        },
        data() {
            return {
                tag: '',
                name: '',
                tags: [],
            };
        },
        mounted() {
            const tag = this.value.value || '';
            this.name = this.value.name || '';

            this.tags = tag.split(',').filter(t => (t && t.trim())).map(t => {
                return {text: t};
            });
        },
        methods: {
            updateValue(newTags) {
                this.tags = newTags;
                this.doUpdateValue();
            },
            doUpdateValue() {
                this.$emit('input', {
                    name: this.name,
                    value: this.tags.map(t => t.text).join(',')
                });
            },
            remove() {
                this.$emit('remove', true);
            }
        }
    }
</script>

<style scoped>

</style>
