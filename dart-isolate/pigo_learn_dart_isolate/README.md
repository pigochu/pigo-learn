Dart isolate 練習
================

## isolate_pass_file ##
測試傳遞 File 給 isolate ，印出 hashCode ，其實 main thread 和 isolate thread 的 File hashCode 不同，是一種 clone

## isolate_pass_stringbuffer ##
測試傳遞 StringBuffer 給 isolate ，isolate thread 修改 buf 後，main thread 的內容不會變，代表 StringBuffer 也是 clone