<template>
<div class="row">
    <div class="col-12 mb-3">
        <form v-on:submit.prevent="addNewItem">
            <div class="form-group">
                <label for="new-item">Add an item</label>
                <div class="input-group">
                    <input id="new-item" class="form-control" v-model="newItemText">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12 mb-3">
        <ul class="list-group">
            <li v-for="(item, index) in items" v-bind:item="item" v-bind:key="item.description" v-on:click="toggleItemStatus(index)" v-bind:class="{'list-group-item list-group-item-action': true, 'list-group-item-success': (item.isDone)}">
                <span v-if="item.isDone"><del>{{ item.description }}</del></span>
                <span v-else>{{ item.description }}</span>
            </li>
        </ul>
    </div>
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-block btn-primary" v-on:click="pushList">Save list</button>
    </div>
    <div class="col-12 mb-3">
        <button type="button" class="btn btn-block btn-secondary" v-on:click="pullList">Reload list</button>
    </div>
    <div class="col-12">
        <button type="button" class="btn btn-block btn-danger" v-on:click="removeCompletedItems">Remove completed items</button>
    </div>
</div>
</template>

<script>
export default {
    data: function () {
        return {
            newItemId: 1,
            newItemText: "",
            items: []
        }
    },
    methods: {
        addNewItem: function () {
            this.items.push({
                id: this.newItemId,
                description: this.newItemText,
                isDone: false
            });
            this.newItemText = "";
            this.newItemId++;
        },
        toggleItemStatus: function (index) {
            this.items[index].isDone = !this.items[index].isDone;
        },
        removeCompletedItems: function () {
            //delete completed items
            for (let i = 0; this.items.length > i; i++) {
                if (this.items[i].isDone) {
                    delete this.items[i];
                }
            }
            //rebuild this.items array without the undefined indexes
            this.items = this.items.filter(function (el) {
                return el != undefined;
            })
        },
        pullList: function () {
            axios.get('/list-items/pull').then((response) => {
                this.items = response.data.listItems;
                this.newItemId = response.data.nextItemId;
            });
        },
        pushList: function () {
            axios.post('/list-items/push',{
                listItems: this.items
            });
        },
    },
    mounted : function () {
        this.pullList();
    }
}
</script>
