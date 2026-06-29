<script setup lang="ts">
import { useNotification } from '@/composables/useNotification';
import NotificationDialog from '@/components/NotificationDialog.vue';

const { isOpen, notification, closeNotification, handleConfirm, handleCancel } = useNotification();
</script>

<template>
    <NotificationDialog
        v-model:open="isOpen"
        :type="notification.type"
        :title="notification.title"
        :message="notification.message"
        :confirm-text="notification.confirmText"
        :cancel-text="notification.cancelText"
        :show-cancel-button="notification.showCancelButton"
        :show-confirm-button="notification.showConfirmButton"
        :close-on-confirm="false"
        :close-on-cancel="false"
        @confirm="handleConfirm"
        @cancel="handleCancel"
        @close="closeNotification"
    />
</template>
