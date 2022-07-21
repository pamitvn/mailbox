const author = (): void => {
    console.group('Author');
    console.log('%cPowered by PAM', 'color: red; font-family:serif; font-size: 20px');
    console.log('Website: https://phamminhan.ceo');
    console.groupEnd();
};

export default author;
