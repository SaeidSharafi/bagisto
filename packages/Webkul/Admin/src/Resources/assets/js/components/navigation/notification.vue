<template>
    <div class="notifications">
        <div class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom" :title="notifTitle">
            <span class="notification-badge" v-if="totalUnRead">{{ totalUnRead }}</span>
            <i class="icon notification-icon active" style="margin-left:0px"></i>
        </div>

        <div class="dropdown-list bottom-right notification">
            <div class="dropdown-container">
                <ul class="notif">
                    <div id="notif-title">{{ title }}</div>

                    <li v-for="notification in notifications" :key="notification.id" :class="notification.read ? 'read': ' '">
                        <div>
                            <span hidden>{{ moment.locale(localeCode) }}</span>
                        </div>

                        <a :href="`${orderViewUrl + notification.order_id}`" v-if="notification.type === 'order'">
                            <div class="notif-icon" :class="notification.order.status">
                                <span :class="ordertype[notification.order.status].icon"></span>
                            </div>

                            <div class="notif-content">
                                #{{ notification.order.id + ' ' + orderTypeMessages[notification.order.status] }}
                            </div>

                            <div class="notif-content">
                                {{ moment(notification.order.created_at).fromNow() }}
                            </div>
                        </a>
                        <a v-if="notification.type === 'rouyesh'" href="#" @click.prevent="readOne(notification)">
                            <div class="notif-icon canceled">
                                <span :class="ordertype['canceled'].icon"></span>
                            </div>

                            <div class="notif-content">
                                {{ notification.message}}
                            </div>

                            <div class="notif-content">
                                {{ moment(notification.created_at).fromNow() }}
                            </div>
                        </a>
                        <a v-else href="#" @click.prevent="readOne(notification)">
                            <div class="notif-icon completed">
                                <span :class="ordertype['completed'].icon"></span>
                            </div>

                            <div class="notif-content">
                                {{ notification.message }}
                            </div>

                            <div class="notif-content">
                                {{ moment(notification.created_at).fromNow() }}
                            </div>
                        </a>
                    </li>

                    <li class="bottom-li">
                        <a :href="viewAll">{{ viewAllTitle }}</a>

                        <button
                            class="read-all"
                            :style="totalUnRead == 0 ? 'opacity: .5' : ''"
                            :disabled="totalUnRead == 0"
                            @click="readAll()"
                        >
                            {{ readAllTitle }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    props: [
        'notifTitle',
        'getNotificationUrl',
        'viewAll',
        'orderViewUrl',
        'pusherKey',
        'pusherCluster',
        'title',
        'viewAllTitle',
        'getReadAllUrl',
        'getReadOneUrl',
        'readAllTitle',
        'orderStatusMessages',
        'localeCode'
    ],

    data() {

        return {
            notifications: [],

            ordertype: {
                pending: {
                    icon: 'pending-icon',
                    message: 'Order Pending'
                },
                processing: {
                    icon: 'processing-icon',
                    message: 'Order Processing'
                },
                canceled: {
                    icon: 'canceled-icon',
                    message: 'Order Canceled'
                },
                completed: {
                    icon: 'completed-icon',
                    message: 'Order Completed'
                },
                closed: {
                    icon: 'closed-icon',
                    message: 'Order Closed'
                }
            },

            totalUnRead: 0,

            orderTypeMessages: JSON.parse(this.orderStatusMessages)
        }
    },

    mounted() {
        this.getNotification();

        if (this.pusherKey != undefined && this.pusherCluster != undefined) {
            Echo = new Echo({
                broadcaster: 'pusher',
                key: this.pusherKey,
                cluster: this.pusherCluster,
                encrypted: true
            });

            Echo.channel('notification').listen('.create-notification', (e) => {
                this.getNotification();
            });

            Echo.channel('notification').listen('.update-notification', (e) => {
                this.notifications.forEach((notification) => {
                    if (notification.order_id == e.id) {
                        notification.order.status = e.status;
                    }
                });
            });
        }
    },

    methods: {
        getNotification: function () {
            const params = {
                limit: 5,
                read: 0
            };

            let this_this = this;

            this.$http.get(this.getNotificationUrl, {
                params: params
            })
                .then(function (response) {
                    this_this.notifications = response.data.search_results.data;
                    this_this.totalUnRead = response.data.total_unread;
                })
                .catch(function (error) {
                })
        },
        readOne: function (notification) {
            if(notification.read === 1){
                return;
            }
            let this_this = this;
            console.log("test");
            console.log(this.getReadOneUrl);
            this.$http.post(this.getReadOneUrl, {
                'id': notification.id
            })
                .then(function (response) {
                    this_this.notifications = response.data.search_results.data;

                    this_this.totalUnRead = response.data.total_unread;

                    window.flashMessages.push({
                        'type': 'alert-success',
                        'message': response.data.success_message
                    });

                    this_this.$root.addFlashMessages();
                })
                .catch(function (error) {
                    console.error(error);
                })
        },
        readAll: function () {
            let this_this = this;

            this.$http.post(this.getReadAllUrl)
                .then(function (response) {
                    this_this.notifications = response.data.search_results.data;

                    this_this.totalUnRead = response.data.total_unread;

                    window.flashMessages.push({
                        'type': 'alert-success',
                        'message': response.data.success_message
                    });

                    this_this.$root.addFlashMessages();
                })
                .catch(function (error) {
                })
        }
    }
}
</script>
