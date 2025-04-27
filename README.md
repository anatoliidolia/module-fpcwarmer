
# PeachCode FPCWarmer Module

## Overview

The PeachCode FPCWarmer module is designed for Magento 2 to generate a queue of URLs and warm the cache for faster page loads. The module interacts with a database to manage URL entries, generate a queue, and initiate the cache warming process.

## Features

- Generates a queue of URLs from the sitemap files.
- Adds records to the table for further processing.
- Fetches URLs from the table and warms the cache.
- Logs the process in a custom log file.
- Can be enabled/disabled via CLI commands and cron jobs.

## Installation

1. **Install the module** by placing the files in the `app/code/PeachCode/FPCWarmer/` directory.
2. **Run the setup upgrade** command to enable the module:
   ```bash
   bin/magento setup:upgrade
   ```
3. **Deploy static content** if in production mode:
   ```bash
   bin/magento setup:static-content:deploy
   ```

## CLI Commands

The module includes two main CLI commands for managing the queue generation and cache warming process.

### Command 1: Generate Queue

**Command:**
```bash
bin/magento peachcode:fpcwarmer:generatequeue
```

This command generates a queue for warmer. It processes sitemaps, extracts the URLs, and stores them in the database for cache warming.

### Command 2: Process Queue

**Command:**
```bash
bin/magento peachcode:fpcwarmer:processqueue
```

This command processes the queue of URLs and warms the cache for each entry in the queue.

## Cron Jobs

To automate the queue generation and cache warming, cron jobs can be configured for both commands:

1. **Generate Queue Cron Job**: This cron job will call the `peachcode:fpcwarmer:generatequeue` command at scheduled intervals.
2. **Process Queue Cron Job**: This cron job will call the `peachcode:fpcwarmer:processqueue` command to process the generated queue and warm the cache.

## Logging

The module logs its activities into a custom log file located at:
```bash
/var/log/fpcwarmer.log
```

All important actions, such as queue generation, URL processing, and cache warming, are recorded in this file for debugging and monitoring.

## Configuration

### Enable/Disable Module

To enable or disable the module, you can use the following commands:

- Enable the module:
  ```bash
  bin/magento module:enable PeachCode_FPCWarmer
  ```
- Disable the module:
  ```bash
  bin/magento module:disable PeachCode_FPCWarmer
  ```

### Customization

- The module can be customized to fit specific requirements by modifying the `QueueGenerator` and `CacheGenerator` or adding new functionality.

## Support

For any issues or support, please contact the module developers at [doliaanatolii@gmail.com](mailto:doliaanatolii@gmail.com).


**Magento 2.4.6**

**Magento 2.4.6.p1-p3**
