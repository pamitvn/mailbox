import type { Form } from '~/types/Components/Form';
import { ref } from 'vue';
import DynamicForm = Form.DynamicForm;
import _ from 'lodash';

const useDynamicForm = <T extends DynamicForm.Fields>(
    fields: T = {} as any,
    defaultValues: Partial<Record<keyof T, any>> = {},
) => {
    const dynamicFormRef = ref<DynamicForm.Expose | null>(null);
    const payload = ref({
        fields,
        defaultValues,
        onSubmit: false,
    });

    const setFields = (values: T) => {
        payload.value.fields = values;
    };
    const setField = <FieldType = DynamicForm.Field>(key: string, field: FieldType) => {
        payload.value.fields[key] = field;
    };
    const setDefaultValues = (values: Partial<Record<keyof T, any>>) => {
        payload.value.defaultValues = values;
    };
    const setDefaultValue = (key: string, value: any, focus = false) => {
        if (!focus && !_.has(payload.value.defaultValues, key)) return;

        payload.value.defaultValues[key] = value;
    };
    const onSubmitForm = () => {
        payload.value.onSubmit = true;
    };

    return {
        dynamicFormRef,
        payload,
        setFields,
        setDefaultValues,
        setField,
        setDefaultValue,
        onSubmitForm,
    };
};

export default useDynamicForm;
