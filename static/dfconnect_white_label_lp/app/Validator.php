<?php

declare(strict_types=1);

final class Validator
{
    public static function validate(array $input, array $options): array
    {
        $fieldErrors = [];
        $errors = [];

        $company = normalize_text($input['company'] ?? '');
        if ($company === '') {
            $fieldErrors['company'] = '会社名を入力してください。';
        } elseif (mb_strlen($company, 'UTF-8') > 255) {
            $fieldErrors['company'] = '会社名は255文字以内で入力してください。';
        }

        $name = normalize_text($input['name'] ?? '');
        if ($name === '') {
            $fieldErrors['name'] = 'お名前を入力してください。';
        } elseif (mb_strlen($name, 'UTF-8') > 100) {
            $fieldErrors['name'] = 'お名前は100文字以内で入力してください。';
        }

        $email = normalize_text($input['email'] ?? '');
        if ($email === '') {
            $fieldErrors['email'] = 'メールアドレスを入力してください。';
        } elseif (preg_match('/[\r\n]/', $email) === 1) {
            $fieldErrors['email'] = 'メールアドレスの形式が正しくありません。';
        } elseif (mb_strlen($email, 'UTF-8') > 255) {
            $fieldErrors['email'] = 'メールアドレスは255文字以内で入力してください。';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $fieldErrors['email'] = 'メールアドレスの形式が正しくありません。';
        }

        $inquiryType = normalize_text($input['inquiry_type'] ?? '');
        if ($inquiryType === '') {
            $fieldErrors['inquiry_type'] = '相談内容を選択してください。';
        } elseif (!self::isValidOptionValue('inquiry_type', $inquiryType, $options)) {
            $fieldErrors['inquiry_type'] = '相談内容の選択値が不正です。';
        }

        $message = normalize_text($input['message'] ?? '');
        if ($message !== '' && mb_strlen($message, 'UTF-8') > 5000) {
            $fieldErrors['message'] = 'メッセージは5000文字以内で入力してください。';
        }

        $privacy = (string) ($input['privacy'] ?? '');
        if ($privacy !== '1') {
            $fieldErrors['privacy'] = 'プライバシーポリシーに同意してください。';
        }

        $companyType = normalize_text($input['company_type'] ?? '');
        if ($companyType !== '' && !self::isValidOptionValue('company_type', $companyType, $options)) {
            $fieldErrors['company_type'] = '会社区分の選択値が不正です。';
        }

        $responseStyle = normalize_text($input['response_style'] ?? '');
        if ($responseStyle !== '' && !self::isValidOptionValue('response_style', $responseStyle, $options)) {
            $fieldErrors['response_style'] = '対応スタイルの選択値が不正です。';
        }

        $desiredTiming = normalize_text($input['desired_timing'] ?? '');
        if ($desiredTiming !== '' && !self::isValidOptionValue('desired_timing', $desiredTiming, $options)) {
            $fieldErrors['desired_timing'] = '希望時期の選択値が不正です。';
        }

        $budgetRange = normalize_text($input['budget_range'] ?? '');
        if ($budgetRange !== '' && !self::isValidOptionValue('budget_range', $budgetRange, $options)) {
            $fieldErrors['budget_range'] = '予算感の選択値が不正です。';
        }

        $nda = normalize_text($input['nda'] ?? '');
        if ($nda !== '' && !self::isValidOptionValue('nda', $nda, $options)) {
            $fieldErrors['nda'] = 'NDA希望の選択値が不正です。';
        }

        $contactMethod = normalize_text($input['contact_method'] ?? '');
        if ($contactMethod !== '' && !self::isValidOptionValue('contact_method', $contactMethod, $options)) {
            $fieldErrors['contact_method'] = '希望の連絡方法の選択値が不正です。';
        }

        foreach ($fieldErrors as $message) {
            $errors[] = $message;
        }

        $data = [
            'company' => $company,
            'name' => $name,
            'email' => $email,
            'company_type' => $companyType,
            'inquiry_type' => $inquiryType,
            'response_style' => $responseStyle,
            'desired_timing' => $desiredTiming,
            'budget_range' => $budgetRange,
            'nda' => $nda,
            'contact_method' => $contactMethod,
            'message' => $message,
            'privacy' => $privacy,
        ];

        return [
            'valid' => empty($fieldErrors),
            'data' => $data,
            'errors' => $errors,
            'field_errors' => $fieldErrors,
        ];
    }

    private static function isValidOptionValue(string $field, string $value, array $options): bool
    {
        return isset($options[$field]) && is_array($options[$field]) && array_key_exists($value, $options[$field]);
    }
}
