<template>
    <div v-if="canUpdate">
        <div class="card mb-4">
            <div class="card-header">Avatar</div>
            <div class="card-body d-flex justify-content-between align-items-center">
                <form
                    method="post"
                    enctype="multipart/form-data"
                    class="align-items-baseline d-flex"
                >
                    <image-upload @loaded="onUpdate"></image-upload>
                </form>
                <div class="d-flex align-items-center">
                    <img :src="avatar" alt width="70" height="70" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ImageUpload from "./ImageUpload";

export default {
    props: ["user"],

    components: { ImageUpload },

    data() {
        return {
            avatar: "/" + this.user.avatar_path
        };
    },
    computed: {
        canUpdate() {
            return this.authorize(user => user.id === this.user.id);
        }
    },
    methods: {
        onUpdate({ src, file }) {
            this.avatar = src;
            this.persist(file);
        },
        persist(avatar) {
            let data = new FormData();

            data.append("avatar", avatar);

            axios
                .post(`/users/${this.user.name}/avatar`, data)
                .then(res => flash("Avatar Updated!"));
        }
    }
};
</script>
