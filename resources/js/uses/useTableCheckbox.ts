import _ from 'lodash';
import { computed, reactive, ref, watchEffect } from 'vue';

const useTableCheckbox = () => {
    const fields = ref({});
    const selected = reactive([]);

    const onSelected = (rows) => {
        fields.value = {};
        fields.value = _.cloneDeep(rows);
    };

    const hasSelected = computed(() => !!selected.length);

    watchEffect(() => {
        selected.splice(0);

        if (!Object.keys(fields.value).length) return;

        _.forEach(_.pickBy(fields.value, i => i), (status, index) => {
            if (!status) return;

            selected.push(index);
        });
    });

    return {
        fields,
        selected,
        hasSelected,
        onSelected,
    };
};

export default useTableCheckbox;
