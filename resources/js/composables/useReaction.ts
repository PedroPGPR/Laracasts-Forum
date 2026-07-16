// composables/useReaction.js
import { router } from '@inertiajs/vue3';
import {
    destroy as destroyDislike,
    store as storeDislike,
} from '@/wayfinder/App/Http/Controllers/DislikeController';
import {
    destroy as destroyLike,
    store as storeLike,
} from '@/wayfinder/App/Http/Controllers/LikeController';

export function useReaction() {
    const like = (model: string, id: number) => {
        router.post(
            storeLike().url,
            { model, id },
            {
                preserveScroll: true,
                preserveState: true,
            },
        );
    };

    const unlike = (model: string, id: number) => {
        router.delete(destroyLike().url, {
            data: { model, id },
            preserveScroll: true,
            preserveState: true,
        });
    };

    const dislike = (model: string, id: number) => {
        router.post(
            storeDislike().url,
            { model, id },
            {
                preserveScroll: true,
                preserveState: true,
            },
        );
    };

    const undislike = (model: string, id: number) => {
        router.delete(destroyDislike().url, {
            data: { model, id },
            preserveScroll: true,
            preserveState: true,
        });
    };

    return {
        like,
        unlike,
        dislike,
        undislike,
    };
}
