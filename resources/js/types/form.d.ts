export interface Field {
    type: string;
    name: string;
    label?: string;
    placeholder?: string;
    required?: boolean;
    options?: Array<{
        label: string;
        value: string | number;
    }>;
    validation?: {
        [key: string]: any;
    };
    defaultValue?: any;
}

export interface FormConfiguration {
    title: string;
    method: 'GET' | 'POST';
    action: string;
    fields: Field[];
}

export interface Form {
    id: number;
    title: string;
    method: string;
    action: string | null;
    configuration: FormConfiguration;
    created_at: string;
    updated_at: string;
}

export interface FormSubmission {
    id: number;
    form_id: number;
    data: Record<string, any>;
    created_at: string;
    updated_at: string;
}
