<template>
    <div>
        <div :id="`reply-${id}`" class="card my-2">
            <div
                class="card-header d-flex justify-content-between align-items-center"
                :class="isBest ? 'bg-success text-light' : ''"
            >
                <div>
                    <a :href="`/profiles/${data.owner.name}`" v-text="data.owner.name"></a>
                    <span>said at</span>
                    <span v-text="ago"></span>
                </div>
                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
            <div class="card-body">
                <div v-if="editing">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body"></textarea>
                    </div>
                    <button class="btn btn-sm btn-primary" @click="update()">Update</button>
                    <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
                </div>
                <div v-else>
                    <div class="body" v-html="body"></div>
                </div>
            </div>
            <div class="card-footer d-flex">
                <div v-show="authorize('updateReply',reply)">
                    <button class="btn btn-sm btn-outline-secondary mr-1" @click="editing = true" >Edit</button>
                    <button type="submit" class="btn btn-outline-danger btn-sm" @click="destroy()" >Delete</button>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm ml-auto" v-show="! isBest" @click="markAsBest()" >Best Reply?</button>
            </div>
        </div>
    </div>
</template>


<script>
import Favorite from "./Favorite";
import moment from "moment";
export default {
    props: ["data"],
    components: {
        Favorite
    },
    data() {
        return {
            editing: false,
            id: this.data.id,
            body: this.data.body,
            isBest: false,
            reply : this.data
        };
    },
    methods: {
        update() {
            axios
                .patch(`/replies/${this.data.id}`, {
                    body: this.body
                })
                .then(res => {
                    flash("Your reply has been updated");
                    this.editing = false;
                })
                .catch(err => flash(err.response.data, "danger"));
        },
        destroy() {
            axios.delete("/replies/" + this.data.id);
            this.$emit("deleted", this.data.id);
        },
        markAsBest() {
            this.isBest = true;
        }
    },
    // updated(){
    //     this.body = this.data.body;
    // },
    computed: {
        ago() {
            return moment(this.data.created_at).fromNow();
        }
    }
};
</script>
