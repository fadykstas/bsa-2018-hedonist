<template>
    <section class="container">
        <Preloader :active="isLoading" />
        <CityPills
            :cities="cities"
            @filter="setCitiesFilter"
        />
        <div class="add-list has-text-right">
            <router-link
                role="button"
                class="button is-success"
                to="/my-lists/add"
            >+ {{ $t('my-lists_page.buttons.add') }}</router-link>
        </div>
        <ul v-show="isLoaded">
            <ListPreview
                v-for="(userList,index,key) in filteredUserLists"
                :key="userList.id"
                :user-list="userList"
                :timer="50 * (key+1)"
                @loading="loading"
            />
        </ul>
        <div class="no-lists-text" v-if="filteredUserLists.length < 1">
            {{ $t('my-lists_page.no-lists') }}
        </div>
    </section>
</template>

<script>
import ListPreview from './ListPreview';
import CityPills from './CityPills';
import { mapState, mapGetters, mapActions } from 'vuex';
import Preloader from '@/components/misc/Preloader';

export default {
    name: 'ListPreviewItems',
    components: {
        ListPreview,
        CityPills,
        Preloader
    },
    data() {
        return {
            isLoading: false,
            filterBy: {
                cityIds: []
            },
        };
    },
    created() {
        if (this.getUserLists.length < 1) {
            this.isLoading = true;
        }

        this.getListsByUser(this.Auth.id)
            .then(()=>{
                this.isLoading = false;
            })
            .catch(()=> {
                this.isLoading = false;
            });
    },
    computed: {
        ...mapState('userList', [
            'userLists',
            'cities',
            'places'
        ]),
        ...mapGetters('auth', {
            Auth: 'getAuthenticatedUser'
        }),
        ...mapGetters('userList', [
            'getFilteredByCity',
            'getUserLists'
        ]),
        isLoaded: function () {
            return !!(this.userLists);
        },
        filteredUserLists: function () {
            let filtered = this.userLists ? this.userLists.byId : null;
            let cityIds = this.filterBy.cityIds;
            if (cityIds.length) {
                filtered = this.getFilteredByCity(filtered, cityIds);
            }

            return this.sortByDesc(filtered);
        }
    },
    methods: {
        ...mapActions('userList', ['getListsByUser']),
        setCitiesFilter(cityIds) {
            this.filterBy.cityIds = cityIds;
        },
        sortByDesc(lists) {
            let listArray = [];
            for (const listId in lists) {
                if (lists.hasOwnProperty(listId)) {
                    listArray.push(lists[listId]);
                }
            }

            return listArray.reverse();
        },
        loading(value) {
            this.isLoading = value;
        }
    },
};
</script>

<style lang="scss" scoped>
    section {
        background: #FFF;
        padding: 50px 10%;
        min-height: calc(100vh - 59px);
    }

    .add-list {
        margin: 20px 0;
    }

    .no-lists-text {
        text-align: center;
        font-size: 1.3rem;
        font-weight: bold;
        margin-top: 50px;

        @media screen and (max-width: 414px) {
            font-size: 1rem;
        }
    }
</style>