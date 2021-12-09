module.exports = {
    publicPath: process.env.NODE_ENV === 'production'
      ? '/testvue/'
      : '/',

    pwa: {
      name: 'Корма для Животных'
    }
}