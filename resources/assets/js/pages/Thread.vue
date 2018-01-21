<script>
    import Replies from '../components/Replies.vue';

    export default {
        props: ['initialRepliesCount'],

        components: { Replies },

        data() {
            return {
                repliesCount: this.initialRepliesCount
            };
        }
        //
        // methods: {
        //     update() {
        //         axios.patch('/replies/' + this.data.id, {
        //             body: this.body
        //         });
        //
        //         this.editing = false;
        //
        //         flash('Updated!');
        //     },
        //     destroy() {
        //         axios.delete('/replies/' + this.data.id);
        //
        //         $(this.$el).fadeOut(300, () => {
        //             flash('Your reply has been deleted.');
        //         });
        //     }
        // }
    }
</script>