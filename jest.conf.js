let path = require('path')

module.export = {
    rootDir: path.resolve(__dirname, '../../'),
      moduleFileExtensions: [
        'js',
        'json',
        'vue',
      ],
      moduleNameMapper: {
        '^@/(.*)$': '<rootDir>/src/$1',
        '\\.(css|less|sass|scss)$': '<rootDir>/test/mock/styleMock.js',   //<-- add this line to fix css import problem
      },
      transform: {
        '^.+\\.js$': '<rootDir>/node_modules/babel-jest',
        '.*\\.(vue)$': '<rootDir>/node_modules/vue-jest',
      },
      transformIgnorePatterns: [
        'node_modules/(?!(bootstrap-vue)/)', // <-- add this line to fix SyntaxError: Unexpected token import
      ],
      testPathIgnorePatterns: [
        '<rootDir>/test/e2e',
      ],
      snapshotSerializers: ['<rootDir>/node_modules/jest-serializer-vue'],
      setupFiles: ['<rootDir>/test/unit/setup'],
      mapCoverage: true,
      coverageDirectory: '<rootDir>/test/unit/coverage',
      collectCoverageFrom: [
        'src/**/*.{js,vue}',
        '!src/main.js',
        '!src/router/index.js',
        '!**/node_modules/**',
      ]
}
