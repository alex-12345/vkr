<template>
    <form novalidate class="md-layout" @submit.prevent="onImageChange">
        <md-card class="md-layout-item">
            <md-card-header>
                <div class="md-title">Изменить аватар</div>
            </md-card-header>

            <md-card-content>
                <div class="md-layout md-gutter">
                    <div class="md-layout-item md-small-size-100">
                        <ImageUploader
                            v-model="selectedImage"
                            src-prefix="/static/img/uploads/"
                            :show-info="false"
                            :allow-clear="true"
                            placeholder="Выберите изображение"
                            id="image0"
                            v-on:image-changed="onImageChange"
                        ></ImageUploader>
                    </div>
                </div>
            </md-card-content>

            <md-card-actions>
                <md-button type="submit" class="md-primary">Сохранить</md-button>
            </md-card-actions>
        </md-card>
    </form>
</template>

<script>
    import ImageUploader from '@/views/Authenticated/Chat/Settings/ImageUploader/ImageUploader';

    export default {
        name: 'ChangeAvatar',
        data: () => ({
            selectedImage: '',
        }),
        methods: {
            onImageChange() {
                console.log('Image: ', this.selectedImage)
            }
        },
        components: {
            ImageUploader
        }
    }
</script>

<style scoped>
    .md-layout {
        margin-top: 40px;
    }

    .md-card {
        width: 100%;
        display: inline-block;
        vertical-align: top;
    }
</style>